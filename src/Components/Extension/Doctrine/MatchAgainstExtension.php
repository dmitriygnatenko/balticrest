<?php

declare(strict_types=1);

namespace App\Components\Extension\Doctrine;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\QueryException;
use Doctrine\ORM\Query\SqlWalker;

class MatchAgainstExtension extends FunctionNode
{
    /** @var null|mixed */
    protected $against = null;

    protected ?array $pathExp = null;

    protected bool $booleanMode = false;

    protected bool $queryExpansion = false;

    /**
     * @param Parser $parser
     *
     * @throws QueryException
     */
    public function parse(Parser $parser)
    {
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->pathExp = [];
        $this->pathExp[] = $parser->StateFieldPathExpression();

        $lexer = $parser->getLexer();

        while ($lexer->isNextToken(Lexer::T_COMMA)) {
            $parser->match(Lexer::T_COMMA);
            $this->pathExp[] = $parser->StateFieldPathExpression();
        }

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);

        if (strtolower($lexer->lookahead['value']) !== 'against') {
            $parser->syntaxError('against');
        }

        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->against = $parser->StringPrimary();

        if (strtolower($lexer->lookahead['value']) === 'boolean') {
            $parser->match(Lexer::T_IDENTIFIER);
            $this->booleanMode = true;
        }

        if (strtolower($lexer->lookahead['value']) === 'expand') {
            $parser->match(Lexer::T_IDENTIFIER);
            $this->queryExpansion = true;
        }

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    /**
     * @param SqlWalker $walker
     *
     * @return string
     */
    public function getSql(SqlWalker $walker)
    {
        $fields = [];

        foreach ($this->pathExp as $pathExp) {
            $fields[] = $pathExp->dispatch($walker);
        }

        $against = $walker->walkStringPrimary($this->against)
            . ($this->booleanMode ? ' IN BOOLEAN MODE' : '')
            . ($this->queryExpansion ? ' WITH QUERY EXPANSION' : '');

        return sprintf('MATCH (%s) AGAINST (%s)', implode(', ', $fields), $against);
    }
}