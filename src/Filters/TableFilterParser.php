<?php

/*
 * Generated from TableFilter.g4 by ANTLR 4.12.0
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
		public const T__0 = 1, OPEN_PAR = 2, CLOSE_PAR = 3, K_IN = 4, K_IS = 5, 
               K_ISNULL = 6, K_LIKE = 7, K_NOT = 8, K_NOTNULL = 9, K_NULL = 10, 
               OPERATOR = 11, LOGICAL_OPERATOR = 12, IDENTIFIER = 13, STRING_LITERAL = 14, 
               SPACES = 15, UNEXPECTED_CHAR = 16;

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
		    null, null, "OPEN_PAR", "CLOSE_PAR", "K_IN", "K_IS", "K_ISNULL", "K_LIKE", 
		    "K_NOT", "K_NOTNULL", "K_NULL", "OPERATOR", "LOGICAL_OPERATOR", "IDENTIFIER", 
		    "STRING_LITERAL", "SPACES", "UNEXPECTED_CHAR"
		];

		private const SERIALIZED_ATN =
			[4, 1, 16, 54, 2, 0, 7, 0, 1, 0, 1, 0, 1, 0, 1, 0, 1, 0, 1, 0, 1, 0, 
		    1, 0, 1, 0, 3, 0, 12, 8, 0, 1, 0, 1, 0, 1, 0, 1, 0, 1, 0, 3, 0, 19, 
		    8, 0, 1, 0, 1, 0, 1, 0, 1, 0, 1, 0, 1, 0, 1, 0, 1, 0, 3, 0, 29, 8, 
		    0, 1, 0, 1, 0, 1, 0, 3, 0, 34, 8, 0, 1, 0, 1, 0, 1, 0, 1, 0, 1, 0, 
		    5, 0, 41, 8, 0, 10, 0, 12, 0, 44, 9, 0, 3, 0, 46, 8, 0, 1, 0, 5, 0, 
		    49, 8, 0, 10, 0, 12, 0, 52, 9, 0, 1, 0, 0, 1, 0, 1, 0, 0, 0, 65, 0, 
		    11, 1, 0, 0, 0, 2, 3, 6, 0, -1, 0, 3, 12, 5, 14, 0, 0, 4, 12, 5, 13, 
		    0, 0, 5, 6, 5, 2, 0, 0, 6, 7, 3, 0, 0, 0, 7, 8, 5, 3, 0, 0, 8, 12, 
		    1, 0, 0, 0, 9, 10, 5, 8, 0, 0, 10, 12, 3, 0, 0, 5, 11, 2, 1, 0, 0, 
		    0, 11, 4, 1, 0, 0, 0, 11, 5, 1, 0, 0, 0, 11, 9, 1, 0, 0, 0, 12, 50, 
		    1, 0, 0, 0, 13, 14, 10, 6, 0, 0, 14, 15, 5, 11, 0, 0, 15, 49, 3, 0, 
		    0, 7, 16, 18, 10, 4, 0, 0, 17, 19, 5, 8, 0, 0, 18, 17, 1, 0, 0, 0, 
		    18, 19, 1, 0, 0, 0, 19, 20, 1, 0, 0, 0, 20, 21, 5, 7, 0, 0, 21, 49, 
		    3, 0, 0, 5, 22, 23, 10, 1, 0, 0, 23, 24, 5, 12, 0, 0, 24, 49, 3, 0, 
		    0, 2, 25, 26, 10, 3, 0, 0, 26, 28, 5, 5, 0, 0, 27, 29, 5, 8, 0, 0, 
		    28, 27, 1, 0, 0, 0, 28, 29, 1, 0, 0, 0, 29, 30, 1, 0, 0, 0, 30, 49, 
		    5, 10, 0, 0, 31, 33, 10, 2, 0, 0, 32, 34, 5, 8, 0, 0, 33, 32, 1, 0, 
		    0, 0, 33, 34, 1, 0, 0, 0, 34, 35, 1, 0, 0, 0, 35, 36, 5, 4, 0, 0, 
		    36, 45, 5, 2, 0, 0, 37, 42, 3, 0, 0, 0, 38, 39, 5, 1, 0, 0, 39, 41, 
		    3, 0, 0, 0, 40, 38, 1, 0, 0, 0, 41, 44, 1, 0, 0, 0, 42, 40, 1, 0, 
		    0, 0, 42, 43, 1, 0, 0, 0, 43, 46, 1, 0, 0, 0, 44, 42, 1, 0, 0, 0, 
		    45, 37, 1, 0, 0, 0, 45, 46, 1, 0, 0, 0, 46, 47, 1, 0, 0, 0, 47, 49, 
		    5, 3, 0, 0, 48, 13, 1, 0, 0, 0, 48, 16, 1, 0, 0, 0, 48, 22, 1, 0, 
		    0, 0, 48, 25, 1, 0, 0, 0, 48, 31, 1, 0, 0, 0, 49, 52, 1, 0, 0, 0, 
		    50, 48, 1, 0, 0, 0, 50, 51, 1, 0, 0, 0, 51, 1, 1, 0, 0, 0, 52, 50, 
		    1, 0, 0, 0, 8, 11, 18, 28, 33, 42, 45, 48, 50];
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

			RuntimeMetaData::checkVersion('4.12.0', RuntimeMetaData::VERSION);

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
				$this->setState(50);
				$this->errorHandler->sync($this);

				$alt = $this->getInterpreter()->adaptivePredict($this->input, 7, $this->ctx);

				while ($alt !== 2 && $alt !== ATN::INVALID_ALT_NUMBER) {
					if ($alt === 1) {
						if ($this->getParseListeners() !== null) {
						    $this->triggerExitRuleEvent();
						}

						$previousContext = $localContext;
						$this->setState(48);
						$this->errorHandler->sync($this);

						switch ($this->getInterpreter()->adaptivePredict($this->input, 6, $this->ctx)) {
							case 1:
							    $localContext = new Context\BinaryExpressionContext(new Context\ExprContext($parentContext, $parentState));
							    $this->pushNewRecursionContext($localContext, $startState, self::RULE_expr);
							    $this->setState(13);

							    if (!($this->precpred($this->ctx, 6))) {
							        throw new FailedPredicateException($this, "\\\$this->precpred(\\\$this->ctx, 6)");
							    }
							    $this->setState(14);
							    $this->match(self::OPERATOR);
							    $this->setState(15);
							    $this->recursiveExpr(7);
							break;

							case 2:
							    $localContext = new Context\LikeExprContext(new Context\ExprContext($parentContext, $parentState));
							    $this->pushNewRecursionContext($localContext, $startState, self::RULE_expr);
							    $this->setState(16);

							    if (!($this->precpred($this->ctx, 4))) {
							        throw new FailedPredicateException($this, "\\\$this->precpred(\\\$this->ctx, 4)");
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
							    $this->recursiveExpr(5);
							break;

							case 3:
							    $localContext = new Context\LogicExpressionContext(new Context\ExprContext($parentContext, $parentState));
							    $this->pushNewRecursionContext($localContext, $startState, self::RULE_expr);
							    $this->setState(22);

							    if (!($this->precpred($this->ctx, 1))) {
							        throw new FailedPredicateException($this, "\\\$this->precpred(\\\$this->ctx, 1)");
							    }
							    $this->setState(23);
							    $this->match(self::LOGICAL_OPERATOR);
							    $this->setState(24);
							    $this->recursiveExpr(2);
							break;

							case 4:
							    $localContext = new Context\IsNullExprContext(new Context\ExprContext($parentContext, $parentState));
							    $this->pushNewRecursionContext($localContext, $startState, self::RULE_expr);
							    $this->setState(25);

							    if (!($this->precpred($this->ctx, 3))) {
							        throw new FailedPredicateException($this, "\\\$this->precpred(\\\$this->ctx, 3)");
							    }
							    $this->setState(26);
							    $this->match(self::K_IS);
							    $this->setState(28);
							    $this->errorHandler->sync($this);
							    $_la = $this->input->LA(1);

							    if ($_la === self::K_NOT) {
							    	$this->setState(27);
							    	$this->match(self::K_NOT);
							    }
							    $this->setState(30);
							    $this->match(self::K_NULL);
							break;

							case 5:
							    $localContext = new Context\InExprContext(new Context\ExprContext($parentContext, $parentState));
							    $this->pushNewRecursionContext($localContext, $startState, self::RULE_expr);
							    $this->setState(31);

							    if (!($this->precpred($this->ctx, 2))) {
							        throw new FailedPredicateException($this, "\\\$this->precpred(\\\$this->ctx, 2)");
							    }
							    $this->setState(33);
							    $this->errorHandler->sync($this);
							    $_la = $this->input->LA(1);

							    if ($_la === self::K_NOT) {
							    	$this->setState(32);
							    	$this->match(self::K_NOT);
							    }
							    $this->setState(35);
							    $this->match(self::K_IN);

							    $this->setState(36);
							    $this->match(self::OPEN_PAR);
							    $this->setState(45);
							    $this->errorHandler->sync($this);
							    $_la = $this->input->LA(1);

							    if (((($_la) & ~0x3f) === 0 && ((1 << $_la) & 24836) !== 0)) {
							    	$this->setState(37);
							    	$this->recursiveExpr(0);
							    	$this->setState(42);
							    	$this->errorHandler->sync($this);

							    	$_la = $this->input->LA(1);
							    	while ($_la === self::T__0) {
							    		$this->setState(38);
							    		$this->match(self::T__0);
							    		$this->setState(39);
							    		$this->recursiveExpr(0);
							    		$this->setState(44);
							    		$this->errorHandler->sync($this);
							    		$_la = $this->input->LA(1);
							    	}
							    }
							    $this->setState(47);
							    $this->match(self::CLOSE_PAR);
							break;
						} 
					}

					$this->setState(52);
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
			        return $this->precpred($this->ctx, 6);

			    case 1:
			        return $this->precpred($this->ctx, 4);

			    case 2:
			        return $this->precpred($this->ctx, 1);

			    case 3:
			        return $this->precpred($this->ctx, 3);

			    case 4:
			        return $this->precpred($this->ctx, 2);
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

	class LogicExpressionContext extends ExprContext
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

	    public function LOGICAL_OPERATOR(): ?TerminalNode
	    {
	        return $this->getToken(TableFilterParser::LOGICAL_OPERATOR, 0);
	    }

		public function accept(ParseTreeVisitor $visitor): mixed
		{
			if ($visitor instanceof TableFilterVisitor) {
			    return $visitor->visitLogicExpression($this);
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