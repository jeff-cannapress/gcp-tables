grammar TableFilter;


expr
 : STRING_LITERAL #strLiteral
 | IDENTIFIER #attributeExpr 
 | OPEN_PAR expr CLOSE_PAR #nestedExpr 
 | expr OPERATOR expr #binaryExpression
 | K_NOT expr #notExpr
 | expr K_NOT? K_LIKE expr  #likeExpr
 | expr K_IS K_NOT? K_NULL #isNullExpr
 | expr K_NOT? K_IN ( OPEN_PAR ( expr ( ',' expr )* )? CLOSE_PAR ) #inExpr
 | expr LOGICAL_OPERATOR expr #logicExpression
 ;

OPEN_PAR : '(';
CLOSE_PAR : ')';
K_IN : [iI] [nN];
K_IS : [iI] [sS];
K_ISNULL : [iI] [sS] [nN] [uU] [lL] [lL];
K_LIKE : [lL] [iI] [kK] [eE];
K_NOT : [nN] [oO] [tT];
K_NOTNULL : [nN] [oO] [tT] [nN] [uU] [lL] [lL];
K_NULL : [nN] [uU] [lL] [lL];


OPERATOR: '<' | '<=' | '>' | '>=' | '==' | '<>' ;
LOGICAL_OPERATOR: 'and' | 'or';

IDENTIFIER
 : [a-zA-Z_] [a-zA-Z_0-9-.[\]]* 
 ;


STRING_LITERAL
 : '\'' ( ~'\'' | '\'\'' )* '\''
 ;


SPACES
 : [ \u000B\t\r\n] -> channel(HIDDEN)
 ;

UNEXPECTED_CHAR
 : .
 ;

