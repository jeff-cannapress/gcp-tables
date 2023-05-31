<?php

/*
 * Generated from TableFilter.g4 by ANTLR 4.13.0
 */

namespace CannaPress\GcpTables\Filters {
	use Antlr\Antlr4\Runtime\Atn\ATN;
	use Antlr\Antlr4\Runtime\Atn\ATNDeserializer;
	use Antlr\Antlr4\Runtime\Atn\ParserATNSimulator;
	use Antlr\Antlr4\Runtime\Dfa\DFA;
	use Antlr\Antlr4\Runtime\Error\Exceptions\FailedPredicateException;
	use Antlr\Antlr4\Runtime\Error\Exceptions\NoViableAltException;
	use Antlr\Antlr4\Runtime\PredictionContexts\PredictionContextCache;
	use Antlr\Antlr4\Runtime\Error\Exceptions\RecognitionException;
	use Antlr\Antlr4\Runtime\RuleContext;
	use Antlr\Antlr4\Runtime\Token;
	use Antlr\Antlr4\Runtime\TokenStream;
	use Antlr\Antlr4\Runtime\Vocabulary;
	use Antlr\Antlr4\Runtime\VocabularyImpl;
	use Antlr\Antlr4\Runtime\RuntimeMetaData;
	use Antlr\Antlr4\Runtime\Parser;

	final class TableFilterParser extends Parser
	{
		public const T__0 = 1, OPEN_PAR = 2, CLOSE_PAR = 3, K_AND = 4, K_IN = 5, 
               K_IS = 6, K_ISNULL = 7, K_LIKE = 8, K_NOT = 9, K_NOTNULL = 10, 
               K_NULL = 11, K_OR = 12, OPERATOR = 13, IDENTIFIER = 14, STRING_LITERAL = 15, 
               SPACES = 16, UNEXPECTED_CHAR = 17;

		public const RULE_expr = 0;

		/**
		 * @var array<string>
		 */
		public const RULE_NAMES = [
			'expr'
		];

		/**
		 * @var array<string|null>
		 */
		private const LITERAL_NAMES = [
		    null, "','", "'('", "')'"
		];

		/**
		 * @var array<string>
		 */
		private const SYMBOLIC_NAMES = [
		    null, null, "OPEN_PAR", "CLOSE_PAR", "K_AND", "K_IN", "K_IS", "K_ISNULL", 
		    "K_LIKE", "K_NOT", "K_NOTNULL", "K_NULL", "K_OR", "OPERATOR", "IDENTIFIER", 
		    "STRING_LITERAL", "SPACES", "UNEXPECTED_CHAR"
		];

		private const SERIALIZED_ATN =
			[4, 1, 17, 51, 2, 0, 7, 0, 1, 0, 1, 0, 1, 0, 1, 0, 1, 0, 1, 0, 1, 0, 
		    1, 0, 1, 0, 3, 0, 12, 8, 0, 1, 0, 1, 0, 1, 0, 1, 0, 1, 0, 3, 0, 19, 
		    8, 0, 1, 0, 1, 0, 1, 0, 1, 0, 1, 0, 3, 0, 26, 8, 0, 1, 0, 1, 0, 1, 
		    0, 3, 0, 31, 8, 0, 1, 0, 1, 0, 1, 0, 1, 0, 1, 0, 5, 0, 38, 8, 0, 10, 
		    0, 12, 0, 41, 9, 0, 3, 0, 43, 8, 0, 1, 0, 5, 0, 46, 8, 0, 10, 0, 12, 
		    0, 49, 9, 0, 1, 0, 0, 1, 0, 1, 0, 0, 0, 61, 0, 11, 1, 0, 0, 0, 2, 
		    3, 6, 0, -1, 0, 3, 12, 5, 15, 0, 0, 4, 12, 5, 14, 0, 0, 5, 6, 5, 2, 
		    0, 0, 6, 7, 3, 0, 0, 0, 7, 8, 5, 3, 0, 0, 8, 12, 1, 0, 0, 0, 9, 10, 
		    5, 9, 0, 0, 10, 12, 3, 0, 0, 5, 11, 2, 1, 0, 0, 0, 11, 4, 1, 0, 0, 
		    0, 11, 5, 1, 0, 0, 0, 11, 9, 1, 0, 0, 0, 12, 47, 1, 0, 0, 0, 13, 14, 
		    10, 4, 0, 0, 14, 15, 5, 13, 0, 0, 15, 46, 3, 0, 0, 5, 16, 18, 10, 
		    3, 0, 0, 17, 19, 5, 9, 0, 0, 18, 17, 1, 0, 0, 0, 18, 19, 1, 0, 0, 
		    0, 19, 20, 1, 0, 0, 0, 20, 21, 5, 8, 0, 0, 21, 46, 3, 0, 0, 4, 22, 
		    23, 10, 2, 0, 0, 23, 25, 5, 6, 0, 0, 24, 26, 5, 9, 0, 0, 25, 24, 1, 
		    0, 0, 0, 25, 26, 1, 0, 0, 0, 26, 27, 1, 0, 0, 0, 27, 46, 5, 11, 0, 
		    0, 28, 30, 10, 1, 0, 0, 29, 31, 5, 9, 0, 0, 30, 29, 1, 0, 0, 0, 30, 
		    31, 1, 0, 0, 0, 31, 32, 1, 0, 0, 0, 32, 33, 5, 5, 0, 0, 33, 42, 5, 
		    2, 0, 0, 34, 39, 3, 0, 0, 0, 35, 36, 5, 1, 0, 0, 36, 38, 3, 0, 0, 
		    0, 37, 35, 1, 0, 0, 0, 38, 41, 1, 0, 0, 0, 39, 37, 1, 0, 0, 0, 39, 
		    40, 1, 0, 0, 0, 40, 43, 1, 0, 0, 0, 41, 39, 1, 0, 0, 0, 42, 34, 1, 
		    0, 0, 0, 42, 43, 1, 0, 0, 0, 43, 44, 1, 0, 0, 0, 44, 46, 5, 3, 0, 
		    0, 45, 13, 1, 0, 0, 0, 45, 16, 1, 0, 0, 0, 45, 22, 1, 0, 0, 0, 45, 
		    28, 1, 0, 0, 0, 46, 49, 1, 0, 0, 0, 47, 45, 1, 0, 0, 0, 47, 48, 1, 
		    0, 0, 0, 48, 1, 1, 0, 0, 0, 49, 47, 1, 0, 0, 0, 8, 11, 18, 25, 30, 
		    39, 42, 45, 47];
		protected static $atn;
		protected static $decisionToDFA;
		protected static $sharedContextCache;

		public function __construct(TokenStream $input)
		{
			parent::__construct($input);

			self::initialize();

			$this->interp = new ParserATNSimulator($this, self::$atn, self::$decisionToDFA, self::$sharedContextCache);
		}

		private static function initialize(): void
		{
			if (self::$atn !== null) {
				return;
			}

			RuntimeMetaData::checkVersion('4.13.0', RuntimeMetaData::VERSION);

			$atn = (new ATNDeserializer())->deserialize(self::SERIALIZED_ATN);

			$decisionToDFA = [];
			for ($i = 0, $count = $atn->getNumberOfDecisions(); $i < $count; $i++) {
				$decisionToDFA[] = new DFA($atn->getDecisionState($i), $i);
			}

			self::$atn = $atn;
			self::$decisionToDFA = $decisionToDFA;
			self::$sharedContextCache = new PredictionContextCache();
		}

		public function getGrammarFileName(): string
		{
			return "TableFilter.g4";
		}

		public function getRuleNames(): array
		{
			return self::RULE_NAMES;
		}

		public function getSerializedATN(): array
		{
			return self::SERIALIZED_ATN;
		}

		public function getATN(): ATN
		{
			return self::$atn;
		}

		public function getVocabulary(): Vocabulary
        {
            static $vocabulary;

			return $vocabulary = $vocabulary ?? new VocabularyImpl(self::LITERAL_NAMES, self::SYMBOLIC_NAMES);
        }

		/**
		 * @throws RecognitionException
		 */
		public function expr(): Context\ExprContext
		{
			return $this->recursiveExpr(0);
		}

		/**
		 * @throws RecognitionException
		 */
		private function recursiveExpr(int $precedence): Context\ExprContext
		{
			$parentContext = $this->ctx;
			$parentState = $this->getState();
			$localContext = new Context\ExprContext($this->ctx, $parentState);
			$previousContext = $localContext;
			$startState = 0;
			$this->enterRecursionRule($localContext, 0, self::RULE_expr, $precedence);

			try {
				$this->enterOuterAlt($localContext, 1);
				$this->setState(11);
				$this->errorHandler->sync($this);

				switch ($this->input->LA(1)) {
				    case self::STRING_LITERAL:
				    	$localContext = new Context\StrLiteralContext($localContext);
				    	$this->ctx = $localContext;
				    	$previousContext = $localContext;

				    	$this->setState(3);
				    	$this->match(self::STRING_LITERAL);
				    	break;

				    case self::IDENTIFIER:
				    	$localContext = new Context\AttributeExprContext($localContext);
				    	$this->ctx = $localContext;
				    	$previousContext = $localContext;
				    	$this->setState(4);
				    	$this->match(self::IDENTIFIER);
				    	break;

				    case self::OPEN_PAR:
				    	$localContext = new Context\NestedExprContext($localContext);
				    	$this->ctx = $localContext;
				    	$previousContext = $localContext;
				    	$this->setState(5);
				    	$this->match(self::OPEN_PAR);
				    	$this->setState(6);
				    	$this->recursiveExpr(0);
				    	$this->setState(7);
				    	$this->match(self::CLOSE_PAR);
				    	break;

				    case self::K_NOT:
				    	$localContext = new Context\NotExprContext($localContext);
				    	$this->ctx = $localContext;
				    	$previousContext = $localContext;
				    	$this->setState(9);
				    	$this->match(self::K_NOT);
				    	$this->setState(10);
				    	$this->recursiveExpr(5);
				    	break;

				default:
					throw new NoViableAltException($this);
				}
				$this->ctx->stop = $this->input->LT(-1);
				$this->setState(47);
				$this->errorHandler->sync($this);

				$alt = $this->getInterpreter()->adaptivePredict($this->input, 7, $this->ctx);

				while ($alt !== 2 && $alt !== ATN::INVALID_ALT_NUMBER) {
					if ($alt === 1) {
						if ($this->getParseListeners() !== null) {
						    $this->triggerExitRuleEvent();
						}

						$previousContext = $localContext;
						$this->setState(45);
						$this->errorHandler->sync($this);

						switch ($this->getInterpreter()->adaptivePredict($this->input, 6, $this->ctx)) {
							case 1:
							    $localContext = new Context\BinaryExpressionContext(new Context\ExprContext($parentContext, $parentState));
							    $this->pushNewRecursionContext($localContext, $startState, self::RULE_expr);
							    $this->setState(13);

							    if (!($this->precpred($this->ctx, 4))) {
							        throw new FailedPredicateException($this, "\\\$this->precpred(\\\$this->ctx, 4)");
							    }
							    $this->setState(14);
							    $this->match(self::OPERATOR);
							    $this->setState(15);
							    $this->recursiveExpr(5);
							break;

							case 2:
							    $localContext = new Context\LikeExprContext(new Context\ExprContext($parentContext, $parentState));
							    $this->pushNewRecursionContext($localContext, $startState, self::RULE_expr);
							    $this->setState(16);

							    if (!($this->precpred($this->ctx, 3))) {
							        throw new FailedPredicateException($this, "\\\$this->precpred(\\\$this->ctx, 3)");
							    }
							    $this->setState(18);
							    $this->errorHandler->sync($this);
							    $_la = $this->input->LA(1);

							    if ($_la === self::K_NOT) {
							    	$this->setState(17);
							    	$this->match(self::K_NOT);
							    }
							    $this->setState(20);
							    $this->match(self::K_LIKE);
							    $this->setState(21);
							    $this->recursiveExpr(4);
							break;

							case 3:
							    $localContext = new Context\IsNullExprContext(new Context\ExprContext($parentContext, $parentState));
							    $this->pushNewRecursionContext($localContext, $startState, self::RULE_expr);
							    $this->setState(22);

							    if (!($this->precpred($this->ctx, 2))) {
							        throw new FailedPredicateException($this, "\\\$this->precpred(\\\$this->ctx, 2)");
							    }
							    $this->setState(23);
							    $this->match(self::K_IS);
							    $this->setState(25);
							    $this->errorHandler->sync($this);
							    $_la = $this->input->LA(1);

							    if ($_la === self::K_NOT) {
							    	$this->setState(24);
							    	$this->match(self::K_NOT);
							    }
							    $this->setState(27);
							    $this->match(self::K_NULL);
							break;

							case 4:
							    $localContext = new Context\InExprContext(new Context\ExprContext($parentContext, $parentState));
							    $this->pushNewRecursionContext($localContext, $startState, self::RULE_expr);
							    $this->setState(28);

							    if (!($this->precpred($this->ctx, 1))) {
							        throw new FailedPredicateException($this, "\\\$this->precpred(\\\$this->ctx, 1)");
							    }
							    $this->setState(30);
							    $this->errorHandler->sync($this);
							    $_la = $this->input->LA(1);

							    if ($_la === self::K_NOT) {
							    	$this->setState(29);
							    	$this->match(self::K_NOT);
							    }
							    $this->setState(32);
							    $this->match(self::K_IN);

							    $this->setState(33);
							    $this->match(self::OPEN_PAR);
							    $this->setState(42);
							    $this->errorHandler->sync($this);
							    $_la = $this->input->LA(1);

							    if (((($_la) & ~0x3f) === 0 && ((1 << $_la) & 49668) !== 0)) {
							    	$this->setState(34);
							    	$this->recursiveExpr(0);
							    	$this->setState(39);
							    	$this->errorHandler->sync($this);

							    	$_la = $this->input->LA(1);
							    	while ($_la === self::T__0) {
							    		$this->setState(35);
							    		$this->match(self::T__0);
							    		$this->setState(36);
							    		$this->recursiveExpr(0);
							    		$this->setState(41);
							    		$this->errorHandler->sync($this);
							    		$_la = $this->input->LA(1);
							    	}
							    }
							    $this->setState(44);
							    $this->match(self::CLOSE_PAR);
							break;
						} 
					}

					$this->setState(49);
					$this->errorHandler->sync($this);

					$alt = $this->getInterpreter()->adaptivePredict($this->input, 7, $this->ctx);
				}
			} catch (RecognitionException $exception) {
				$localContext->exception = $exception;
				$this->errorHandler->reportError($this, $exception);
				$this->errorHandler->recover($this, $exception);
			} finally {
				$this->unrollRecursionContexts($parentContext);
			}

			return $localContext;
		}

		public function sempred(?RuleContext $localContext, int $ruleIndex, int $predicateIndex): bool
		{
			switch ($ruleIndex) {
					case 0:
						return $this->sempredExpr($localContext, $predicateIndex);

				default:
					return true;
				}
		}

		private function sempredExpr(?Context\ExprContext $localContext, int $predicateIndex): bool
		{
			switch ($predicateIndex) {
			    case 0:
			        return $this->precpred($this->ctx, 4);

			    case 1:
			        return $this->precpred($this->ctx, 3);

			    case 2:
			        return $this->precpred($this->ctx, 2);

			    case 3:
			        return $this->precpred($this->ctx, 1);
			}

			return true;
		}
	}
}

namespace CannaPress\GcpTables\Filters\Context {
	use Antlr\Antlr4\Runtime\ParserRuleContext;
	use Antlr\Antlr4\Runtime\Token;
	use Antlr\Antlr4\Runtime\Tree\ParseTreeVisitor;
	use Antlr\Antlr4\Runtime\Tree\TerminalNode;
	use Antlr\Antlr4\Runtime\Tree\ParseTreeListener;
	use CannaPress\GcpTables\Filters\TableFilterParser;
	use CannaPress\GcpTables\Filters\TableFilterVisitor;

	class ExprContext extends ParserRuleContext
	{
		public function __construct(?ParserRuleContext $parent, ?int $invokingState = null)
		{
			parent::__construct($parent, $invokingState);
		}

		public function getRuleIndex(): int
		{
		    return TableFilterParser::RULE_expr;
	    }
	 
		public function copyFrom(ParserRuleContext $context): void
		{
			parent::copyFrom($context);

		}
	}

	class StrLiteralContext extends ExprContext
	{
		public function __construct(ExprContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    public function STRING_LITERAL(): ?TerminalNode
	    {
	        return $this->getToken(TableFilterParser::STRING_LITERAL, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof TableFilterVisitor) {
			    return $visitor->visitStrLiteral($this);
		    }

			return $visitor->visitChildren($this);
		}
	}

	class BinaryExpressionContext extends ExprContext
	{
		public function __construct(ExprContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    /**
	     * @return array<ExprContext>|ExprContext|null
	     */
	    public function expr(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(ExprContext::class);
	    	}

	        return $this->getTypedRuleContext(ExprContext::class, $index);
	    }

	    public function OPERATOR(): ?TerminalNode
	    {
	        return $this->getToken(TableFilterParser::OPERATOR, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof TableFilterVisitor) {
			    return $visitor->visitBinaryExpression($this);
		    }

			return $visitor->visitChildren($this);
		}
	}

	class NotExprContext extends ExprContext
	{
		public function __construct(ExprContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    public function K_NOT(): ?TerminalNode
	    {
	        return $this->getToken(TableFilterParser::K_NOT, 0);
	    }

	    public function expr(): ?ExprContext
	    {
	    	return $this->getTypedRuleContext(ExprContext::class, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof TableFilterVisitor) {
			    return $visitor->visitNotExpr($this);
		    }

			return $visitor->visitChildren($this);
		}
	}

	class IsNullExprContext extends ExprContext
	{
		public function __construct(ExprContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    public function expr(): ?ExprContext
	    {
	    	return $this->getTypedRuleContext(ExprContext::class, 0);
	    }

	    public function K_IS(): ?TerminalNode
	    {
	        return $this->getToken(TableFilterParser::K_IS, 0);
	    }

	    public function K_NULL(): ?TerminalNode
	    {
	        return $this->getToken(TableFilterParser::K_NULL, 0);
	    }

	    public function K_NOT(): ?TerminalNode
	    {
	        return $this->getToken(TableFilterParser::K_NOT, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof TableFilterVisitor) {
			    return $visitor->visitIsNullExpr($this);
		    }

			return $visitor->visitChildren($this);
		}
	}

	class InExprContext extends ExprContext
	{
		public function __construct(ExprContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    /**
	     * @return array<ExprContext>|ExprContext|null
	     */
	    public function expr(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(ExprContext::class);
	    	}

	        return $this->getTypedRuleContext(ExprContext::class, $index);
	    }

	    public function K_IN(): ?TerminalNode
	    {
	        return $this->getToken(TableFilterParser::K_IN, 0);
	    }

	    public function OPEN_PAR(): ?TerminalNode
	    {
	        return $this->getToken(TableFilterParser::OPEN_PAR, 0);
	    }

	    public function CLOSE_PAR(): ?TerminalNode
	    {
	        return $this->getToken(TableFilterParser::CLOSE_PAR, 0);
	    }

	    public function K_NOT(): ?TerminalNode
	    {
	        return $this->getToken(TableFilterParser::K_NOT, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof TableFilterVisitor) {
			    return $visitor->visitInExpr($this);
		    }

			return $visitor->visitChildren($this);
		}
	}

	class NestedExprContext extends ExprContext
	{
		public function __construct(ExprContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    public function OPEN_PAR(): ?TerminalNode
	    {
	        return $this->getToken(TableFilterParser::OPEN_PAR, 0);
	    }

	    public function expr(): ?ExprContext
	    {
	    	return $this->getTypedRuleContext(ExprContext::class, 0);
	    }

	    public function CLOSE_PAR(): ?TerminalNode
	    {
	        return $this->getToken(TableFilterParser::CLOSE_PAR, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof TableFilterVisitor) {
			    return $visitor->visitNestedExpr($this);
		    }

			return $visitor->visitChildren($this);
		}
	}

	class LikeExprContext extends ExprContext
	{
		public function __construct(ExprContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    /**
	     * @return array<ExprContext>|ExprContext|null
	     */
	    public function expr(?int $index = null)
	    {
	    	if ($index === null) {
	    		return $this->getTypedRuleContexts(ExprContext::class);
	    	}

	        return $this->getTypedRuleContext(ExprContext::class, $index);
	    }

	    public function K_LIKE(): ?TerminalNode
	    {
	        return $this->getToken(TableFilterParser::K_LIKE, 0);
	    }

	    public function K_NOT(): ?TerminalNode
	    {
	        return $this->getToken(TableFilterParser::K_NOT, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof TableFilterVisitor) {
			    return $visitor->visitLikeExpr($this);
		    }

			return $visitor->visitChildren($this);
		}
	}

	class AttributeExprContext extends ExprContext
	{
		public function __construct(ExprContext $context)
		{
		    parent::__construct($context);

		    $this->copyFrom($context);
	    }

	    public function IDENTIFIER(): ?TerminalNode
	    {
	        return $this->getToken(TableFilterParser::IDENTIFIER, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof TableFilterVisitor) {
			    return $visitor->visitAttributeExpr($this);
		    }

			return $visitor->visitChildren($this);
		}
	} 
}