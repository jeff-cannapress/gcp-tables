<?php

/*
 * Generated from TableFilter.g4 by ANTLR 4.13.0
 */

namespace CannaPress\GcpTables\Filters;

use Antlr\Antlr4\Runtime\Tree\ParseTreeVisitor;

/**
 * This interface defines a complete generic visitor for a parse tree produced by {@see TableFilterParser}.
 */
interface TableFilterVisitor extends ParseTreeVisitor
{
	/**
	 * Visit a parse tree produced by the `strLiteral` labeled alternative
	 * in {@see TableFilterParser::expr()}.
	 *
	 * @param Context\StrLiteralContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitStrLiteral(Context\StrLiteralContext $context);

	/**
	 * Visit a parse tree produced by the `binaryExpression` labeled alternative
	 * in {@see TableFilterParser::expr()}.
	 *
	 * @param Context\BinaryExpressionContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitBinaryExpression(Context\BinaryExpressionContext $context);

	/**
	 * Visit a parse tree produced by the `notExpr` labeled alternative
	 * in {@see TableFilterParser::expr()}.
	 *
	 * @param Context\NotExprContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitNotExpr(Context\NotExprContext $context);

	/**
	 * Visit a parse tree produced by the `isNullExpr` labeled alternative
	 * in {@see TableFilterParser::expr()}.
	 *
	 * @param Context\IsNullExprContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitIsNullExpr(Context\IsNullExprContext $context);

	/**
	 * Visit a parse tree produced by the `inExpr` labeled alternative
	 * in {@see TableFilterParser::expr()}.
	 *
	 * @param Context\InExprContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitInExpr(Context\InExprContext $context);

	/**
	 * Visit a parse tree produced by the `nestedExpr` labeled alternative
	 * in {@see TableFilterParser::expr()}.
	 *
	 * @param Context\NestedExprContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitNestedExpr(Context\NestedExprContext $context);

	/**
	 * Visit a parse tree produced by the `likeExpr` labeled alternative
	 * in {@see TableFilterParser::expr()}.
	 *
	 * @param Context\LikeExprContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitLikeExpr(Context\LikeExprContext $context);

	/**
	 * Visit a parse tree produced by the `attributeExpr` labeled alternative
	 * in {@see TableFilterParser::expr()}.
	 *
	 * @param Context\AttributeExprContext $context The parse tree.
	 *
	 * @return mixed The visitor result.
	 */
	public function visitAttributeExpr(Context\AttributeExprContext $context);
}