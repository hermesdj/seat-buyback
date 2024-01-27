<?php

namespace H4zz4rdDev\Seat\SeatBuyback\Parser;

use RecursiveTree\Seat\TreeLib\Items\EveItem;
use RecursiveTree\Seat\TreeLib\Parser\FitParser;
use RecursiveTree\Seat\TreeLib\Parser\ItemListParser;
use RecursiveTree\Seat\TreeLib\Parser\ManualBuyPrices;
use RecursiveTree\Seat\TreeLib\Parser\MultibuyParser;
use RecursiveTree\Seat\TreeLib\Parser\NewInventoryWindowParser;
use RecursiveTree\Seat\TreeLib\Parser\ParseResult;
use Seat\Eveapi\Models\Sde\InvGroup;
use Seat\Eveapi\Models\Sde\InvType;

class InventoryParser extends NewInventoryWindowParser
{
    /**
     * Modified for french compatibility
     */
    protected const BIG_NUMBER_REGEXP = "(?:\d+(?:[’\s+,]\d\d\d)*(?:\.\d\d)?)";

    /**
     * @param $text
     * @param string $EveItemClass
     * @return ParseResult
     */
    static function parseItems($text, string $EveItemClass = EveItem::class): ParseResult
    {
        $text = preg_replace('~\R~u', "\n", $text);
        $text = preg_replace('~\x{b3}~u', '3', $text);
        $text = preg_replace('/\xc2\xa0/', ' ', $text);

        //from specific to broad
        $parsers = [
            InventoryParser::class,
            //fits
            FitParser::class,
            // the ingame multibuy
            MultibuyParser::class,
            // the old multibuy, but also support prices
            ManualBuyPrices::class,
            //also bytes on ingame multibuys, so handle it afterwards
            NewInventoryWindowParser::class,
            ItemListParser::class
        ];

        foreach ($parsers as $parser) {
            $parsed = $parser::parse($text, $EveItemClass);
            if ($parsed !== null) {
                //dd($parser, $parsed);
                $parsed->_debug_parser = $parser;
                return $parsed;
            }
        }

        $result = new ParseResult(collect());
        $result->warning = true;
        $result->_debug_parser = null;
        return $result;
    }

    /**
     * @param string $text
     * @param string $EveItemClass
     * @return ParseResult|null
     */
    protected static function parse(string $text, string $EveItemClass): ?ParseResult
    {
        $expr = implode("", [
            "^(?<name>[^\t*]+)\*?",                                            //name
            "\t(?<amount>" . self::BIG_NUMBER_REGEXP . "?)",             //amount
            "(?:\t(?<group>\D[^\t]*))?",                                  //group
            "(?:\t(?<category>\D[^\t]*))?",                               //category
            "(?:\t(?<size>\D[^\t]*)?)?",                                   //size. seems to be empty
            "(?:\t(?<slot>\D[^\t]*)?)?",                                   //slot
            "(?:\t(?<volume>" . self::BIG_NUMBER_REGEXP . ") m3)?",         //volume
            "(?:\t(?<meta>" . self::BIG_NUMBER_REGEXP . ")?)?",              //meta level
            "(?:\t(?<tech>" . self::BIG_NUMBER_REGEXP . "|None))?",         //tech level
            "(?:\t(?:(?<price>" . self::BIG_NUMBER_REGEXP . ") ISK)?)?",           //Est. price
            "$"                                                         //end
        ]);

        //dd($expr);
        $lines = self::matchLines($expr, $text);

        //check if there are any matches
        if ($lines->where("match", "!=", null)->isEmpty()) return null;

        $warning = false;
        $items = [];

        foreach ($lines as $line) {
            //if the line doesn't match, continue
            if ($line->match === null) continue;

            $groupID = null;

            //get the type from the name
            $type_model_query = InvType::where("typeName", $line->match->name);
            //check if the group matches to detected items named like a item
            if ($line->match->group) {
                self::getGroupID($line->match, $groupID);

                if ($groupID) $type_model_query = $type_model_query->where("groupID", $groupID);
            }
            //TODO category check once model is in core
            //get the model
            $type_model = $type_model_query->first();

            //amount
            $amount = self::parseBigNumber($line->match->amount);
            if ($amount == null) $amount = 1;
            if ($amount < 1) $amount = 1;

            //if we can't find the type over the name or the amount is not specified, it is a named item.
            $is_named = $type_model === null || $line->match->amount === null;

            //volume
            $volume = self::parseBigNumber($line->match->volume);
            if ($volume !== null) $volume = $volume / $amount;

            //if we can't guess the type from the name
            if ($type_model === null) {
                $type_model = self::determineItemType($line->match, $volume, $groupID);
            }

            //if we still don't have the type, ignore it
            if ($type_model === null) {
                $warning = true;
                continue;
            }

            $item = new $EveItemClass($type_model);
            $item->amount = $amount;
            $item->volume = $volume;
            $item->ingamePrice = self::parseBigNumber($line->match->price);
            $item->is_named = $is_named;
            $items[] = $item;
        }

        if (count($items) < 1) return null;

        $result = new ParseResult(collect($items));
        $result->warning = $warning;
        return $result;
    }

    /**
     * @param $match
     * @param int|null $volume
     * @param int|null $groupID
     * @return InvType|null
     */
    private static function determineItemType($match, ?int $volume, ?int $groupID): ?InvType
    {
        self::getGroupID($match, $groupID);

        $query = InvType::query();

        if ($groupID !== null) {
            $query = $query->where("groupID", $groupID);
        }

        if ($volume !== null) {
            $query = $query->where("volume", $volume);
        }

        $items = $query->limit(2)->get();
        if ($items->count() == 1) return $items->get(0);

        return null;
    }

    private static function getGroupID($match, &$groupID): void
    {
        if ($groupID === null) $groupID = InvGroup::where("groupName", $match->group)->first()->groupID ?? null;
    }

    protected static function parseBigNumber($number): ?float
    {
        if ($number === null) return null;
        return floatval(str_replace(["’", " ", ","], "", $number));
    }
}