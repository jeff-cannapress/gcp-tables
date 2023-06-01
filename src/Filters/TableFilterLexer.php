<?php

/*
 * Generated from TableFilter.g4 by ANTLR 4.12.0
 */

namespace CannaPress\GcpTables\Filters {
	use Antlr\Antlr4\Runtime\Atn\ATNDeserializer;
	use Antlr\Antlr4\Runtime\Atn\LexerATNSimulator;
	use Antlr\Antlr4\Runtime\Lexer;
	use Antlr\Antlr4\Runtime\CharStream;
	use Antlr\Antlr4\Runtime\PredictionContexts\PredictionContextCache;
	use Antlr\Antlr4\Runtime\RuleContext;
	use Antlr\Antlr4\Runtime\Atn\ATN;
	use Antlr\Antlr4\Runtime\Dfa\DFA;
	use Antlr\Antlr4\Runtime\Vocabulary;
	use Antlr\Antlr4\Runtime\RuntimeMetaData;
	use Antlr\Antlr4\Runtime\VocabularyImpl;

	final class TableFilterLexer extends Lexer
	{
		public const T__0 = 1, OPEN_PAR = 2, CLOSE_PAR = 3, K_IN = 4, K_IS = 5, 
               K_ISNULL = 6, K_LIKE = 7, K_NOT = 8, K_NOTNULL = 9, K_NULL = 10, 
               OPERATOR = 11, LOGICAL_OPERATOR = 12, IDENTIFIER = 13, STRING_LITERAL = 14, 
               SPACES = 15, UNEXPECTED_CHAR = 16;

		/**
		 * @var array<string>
		 */
		public const CHANNEL_NAMES = [
			'DEFAULT_TOKEN_CHANNEL', 'HIDDEN'
		];

		/**
		 * @var array<string>
		 */
		public const MODE_NAMES = [
			'DEFAULT_MODE'
		];

		/**
		 * @var array<string>
		 */
		public const RULE_NAMES = [
			'T__0', 'OPEN_PAR', 'CLOSE_PAR', 'K_IN', 'K_IS', 'K_ISNULL', 'K_LIKE', 
			'K_NOT', 'K_NOTNULL', 'K_NULL', 'OPERATOR', 'LOGICAL_OPERATOR', 'IDENTIFIER', 
			'STRING_LITERAL', 'SPACES', 'UNEXPECTED_CHAR'
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
			[4, 0, 16, 117, 6, -1, 2, 0, 7, 0, 2, 1, 7, 1, 2, 2, 7, 2, 2, 3, 7, 3, 
		    2, 4, 7, 4, 2, 5, 7, 5, 2, 6, 7, 6, 2, 7, 7, 7, 2, 8, 7, 8, 2, 9, 
		    7, 9, 2, 10, 7, 10, 2, 11, 7, 11, 2, 12, 7, 12, 2, 13, 7, 13, 2, 14, 
		    7, 14, 2, 15, 7, 15, 1, 0, 1, 0, 1, 1, 1, 1, 1, 2, 1, 2, 1, 3, 1, 
		    3, 1, 3, 1, 4, 1, 4, 1, 4, 1, 5, 1, 5, 1, 5, 1, 5, 1, 5, 1, 5, 1, 
		    5, 1, 6, 1, 6, 1, 6, 1, 6, 1, 6, 1, 7, 1, 7, 1, 7, 1, 7, 1, 8, 1, 
		    8, 1, 8, 1, 8, 1, 8, 1, 8, 1, 8, 1, 8, 1, 9, 1, 9, 1, 9, 1, 9, 1, 
		    9, 1, 10, 1, 10, 1, 10, 1, 10, 1, 10, 1, 10, 1, 10, 1, 10, 1, 10, 
		    1, 10, 3, 10, 85, 8, 10, 1, 11, 1, 11, 1, 11, 1, 11, 1, 11, 3, 11, 
		    92, 8, 11, 1, 12, 1, 12, 5, 12, 96, 8, 12, 10, 12, 12, 12, 99, 9, 
		    12, 1, 13, 1, 13, 1, 13, 1, 13, 5, 13, 105, 8, 13, 10, 13, 12, 13, 
		    108, 9, 13, 1, 13, 1, 13, 1, 14, 1, 14, 1, 14, 1, 14, 1, 15, 1, 15, 
		    0, 0, 16, 1, 1, 3, 2, 5, 3, 7, 4, 9, 5, 11, 6, 13, 7, 15, 8, 17, 9, 
		    19, 10, 21, 11, 23, 12, 25, 13, 27, 14, 29, 15, 31, 16, 1, 0, 13, 
		    2, 0, 73, 73, 105, 105, 2, 0, 78, 78, 110, 110, 2, 0, 83, 83, 115, 
		    115, 2, 0, 85, 85, 117, 117, 2, 0, 76, 76, 108, 108, 2, 0, 75, 75, 
		    107, 107, 2, 0, 69, 69, 101, 101, 2, 0, 79, 79, 111, 111, 2, 0, 84, 
		    84, 116, 116, 3, 0, 65, 90, 95, 95, 97, 122, 5, 0, 45, 46, 48, 57, 
		    65, 90, 95, 95, 97, 122, 1, 0, 39, 39, 3, 0, 9, 11, 13, 13, 32, 32, 
		    125, 0, 1, 1, 0, 0, 0, 0, 3, 1, 0, 0, 0, 0, 5, 1, 0, 0, 0, 0, 7, 1, 
		    0, 0, 0, 0, 9, 1, 0, 0, 0, 0, 11, 1, 0, 0, 0, 0, 13, 1, 0, 0, 0, 0, 
		    15, 1, 0, 0, 0, 0, 17, 1, 0, 0, 0, 0, 19, 1, 0, 0, 0, 0, 21, 1, 0, 
		    0, 0, 0, 23, 1, 0, 0, 0, 0, 25, 1, 0, 0, 0, 0, 27, 1, 0, 0, 0, 0, 
		    29, 1, 0, 0, 0, 0, 31, 1, 0, 0, 0, 1, 33, 1, 0, 0, 0, 3, 35, 1, 0, 
		    0, 0, 5, 37, 1, 0, 0, 0, 7, 39, 1, 0, 0, 0, 9, 42, 1, 0, 0, 0, 11, 
		    45, 1, 0, 0, 0, 13, 52, 1, 0, 0, 0, 15, 57, 1, 0, 0, 0, 17, 61, 1, 
		    0, 0, 0, 19, 69, 1, 0, 0, 0, 21, 84, 1, 0, 0, 0, 23, 91, 1, 0, 0, 
		    0, 25, 93, 1, 0, 0, 0, 27, 100, 1, 0, 0, 0, 29, 111, 1, 0, 0, 0, 31, 
		    115, 1, 0, 0, 0, 33, 34, 5, 44, 0, 0, 34, 2, 1, 0, 0, 0, 35, 36, 5, 
		    40, 0, 0, 36, 4, 1, 0, 0, 0, 37, 38, 5, 41, 0, 0, 38, 6, 1, 0, 0, 
		    0, 39, 40, 7, 0, 0, 0, 40, 41, 7, 1, 0, 0, 41, 8, 1, 0, 0, 0, 42, 
		    43, 7, 0, 0, 0, 43, 44, 7, 2, 0, 0, 44, 10, 1, 0, 0, 0, 45, 46, 7, 
		    0, 0, 0, 46, 47, 7, 2, 0, 0, 47, 48, 7, 1, 0, 0, 48, 49, 7, 3, 0, 
		    0, 49, 50, 7, 4, 0, 0, 50, 51, 7, 4, 0, 0, 51, 12, 1, 0, 0, 0, 52, 
		    53, 7, 4, 0, 0, 53, 54, 7, 0, 0, 0, 54, 55, 7, 5, 0, 0, 55, 56, 7, 
		    6, 0, 0, 56, 14, 1, 0, 0, 0, 57, 58, 7, 1, 0, 0, 58, 59, 7, 7, 0, 
		    0, 59, 60, 7, 8, 0, 0, 60, 16, 1, 0, 0, 0, 61, 62, 7, 1, 0, 0, 62, 
		    63, 7, 7, 0, 0, 63, 64, 7, 8, 0, 0, 64, 65, 7, 1, 0, 0, 65, 66, 7, 
		    3, 0, 0, 66, 67, 7, 4, 0, 0, 67, 68, 7, 4, 0, 0, 68, 18, 1, 0, 0, 
		    0, 69, 70, 7, 1, 0, 0, 70, 71, 7, 3, 0, 0, 71, 72, 7, 4, 0, 0, 72, 
		    73, 7, 4, 0, 0, 73, 20, 1, 0, 0, 0, 74, 85, 5, 60, 0, 0, 75, 76, 5, 
		    60, 0, 0, 76, 85, 5, 61, 0, 0, 77, 85, 5, 62, 0, 0, 78, 79, 5, 62, 
		    0, 0, 79, 85, 5, 61, 0, 0, 80, 81, 5, 61, 0, 0, 81, 85, 5, 61, 0, 
		    0, 82, 83, 5, 60, 0, 0, 83, 85, 5, 62, 0, 0, 84, 74, 1, 0, 0, 0, 84, 
		    75, 1, 0, 0, 0, 84, 77, 1, 0, 0, 0, 84, 78, 1, 0, 0, 0, 84, 80, 1, 
		    0, 0, 0, 84, 82, 1, 0, 0, 0, 85, 22, 1, 0, 0, 0, 86, 87, 5, 97, 0, 
		    0, 87, 88, 5, 110, 0, 0, 88, 92, 5, 100, 0, 0, 89, 90, 5, 111, 0, 
		    0, 90, 92, 5, 114, 0, 0, 91, 86, 1, 0, 0, 0, 91, 89, 1, 0, 0, 0, 92, 
		    24, 1, 0, 0, 0, 93, 97, 7, 9, 0, 0, 94, 96, 7, 10, 0, 0, 95, 94, 1, 
		    0, 0, 0, 96, 99, 1, 0, 0, 0, 97, 95, 1, 0, 0, 0, 97, 98, 1, 0, 0, 
		    0, 98, 26, 1, 0, 0, 0, 99, 97, 1, 0, 0, 0, 100, 106, 5, 39, 0, 0, 
		    101, 105, 8, 11, 0, 0, 102, 103, 5, 39, 0, 0, 103, 105, 5, 39, 0, 
		    0, 104, 101, 1, 0, 0, 0, 104, 102, 1, 0, 0, 0, 105, 108, 1, 0, 0, 
		    0, 106, 104, 1, 0, 0, 0, 106, 107, 1, 0, 0, 0, 107, 109, 1, 0, 0, 
		    0, 108, 106, 1, 0, 0, 0, 109, 110, 5, 39, 0, 0, 110, 28, 1, 0, 0, 
		    0, 111, 112, 7, 12, 0, 0, 112, 113, 1, 0, 0, 0, 113, 114, 6, 14, 0, 
		    0, 114, 30, 1, 0, 0, 0, 115, 116, 9, 0, 0, 0, 116, 32, 1, 0, 0, 0, 
		    6, 0, 84, 91, 97, 104, 106, 1, 0, 1, 0];
		protected static $atn;
		protected static $decisionToDFA;
		protected static $sharedContextCache;
		public function __construct(CharStream $input)
		{
			parent::__construct($input);

			self::initialize();

			$this->interp = new LexerATNSimulator($this, self::$atn, self::$decisionToDFA, self::$sharedContextCache);
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

		public static function vocabulary(): Vocabulary
		{
			static $vocabulary;

			return $vocabulary = $vocabulary ?? new VocabularyImpl(self::LITERAL_NAMES, self::SYMBOLIC_NAMES);
		}

		public function getGrammarFileName(): string
		{
			return 'TableFilter.g4';
		}

		public function getRuleNames(): array
		{
			return self::RULE_NAMES;
		}

		public function getSerializedATN(): array
		{
			return self::SERIALIZED_ATN;
		}

		/**
		 * @return array<string>
		 */
		public function getChannelNames(): array
		{
			return self::CHANNEL_NAMES;
		}

		/**
		 * @return array<string>
		 */
		public function getModeNames(): array
		{
			return self::MODE_NAMES;
		}

		public function getATN(): ATN
		{
			return self::$atn;
		}

		public function getVocabulary(): Vocabulary
		{
			return self::vocabulary();
		}
	}
}