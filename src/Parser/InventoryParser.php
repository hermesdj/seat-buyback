<?php

namespace H4zz4rdDev\Seat\SeatBuyback\Parser;

use RecursiveTree\Seat\TreeLib\Items\EveItem;
use RecursiveTree\Seat\TreeLib\Parser\FitParser;
use RecursiveTree\Seat\TreeLib\Parser\ItemListParser;
use RecursiveTree\Seat\TreeLib\Parser\ManualBuyPrices;
use RecursiveTree\Seat\TreeLib\Parser\MultibuyParser;
use RecursiveTree\Seat\TreeLib\Parser\NewInventoryWindowParser;
use RecursiveTree\Seat\TreeLib\Parser\ParseResult;

class InventoryParser extends NewInventoryWindowParser
{
    /**
     * Modified for french compatibility
     */
    protected const BIG_NUMBER_REGEXP = "(?:\d+(?:[’ ,]\d\d\d)*(?:\.\d\d)?)";

    /**
     * @param $text
     * @param string $EveItemClass
     * @return ParseResult
     */
    static function parseItems($text, string $EveItemClass = EveItem::class): ParseResult
    {
        $text = preg_replace('~\R~u', "\n", $text);

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
}