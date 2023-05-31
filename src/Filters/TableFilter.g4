grammar TableFilter;


expr
 : STRING_LITERAL #strLiteral
 | IDENTIFIER #attributeExpr 
 | OPEN_PAR expr CLOSE_PAR #nestedExpr 
 | K_NOT expr #notExpr
 | expr OPERATOR expr #binaryExpression
 | expr K_NOT? K_LIKE expr  #likeExpr
 | expr K_IS K_NOT? K_NULL #isNullExpr
 | expr K_NOT? K_IN ( OPEN_PAR ( expr ( ',' expr )* )? CLOSE_PAR ) #inExpr
 ;



OPEN_PAR : '(';
CLOSE_PAR : ')';
K_AND : A N D;
K_IN : I N;
K_IS : I S;
K_ISNULL : I S N U L L;
K_LIKE : L I K E;
K_NOT : N O T;
K_NOTNULL : N O T N U L L;
K_NULL : N U L L;
K_OR : O R;

OPERATOR: '<' | '<=' | '>' | '>=' | '==' | '<>' | 'and' | 'or';




IDENTIFIER
 : [a-zA-Z_] [a-zA-Z_0-9-.]* // TODO check: needs more chars in set
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

fragment DIGIT : [0-9];
fragment LETTER:[a-zA-Z_];
fragment A : [aA];
fragment B : [bB];
fragment C : [cC];
fragment D : [dD];
fragment E : [eE];
fragment F : [fF];
fragment G : [gG];
fragment H : [hH];
fragment I : [iI];
fragment J : [jJ];
fragment K : [kK];
fragment L : [lL];
fragment M : [mM];
fragment N : [nN];
fragment O : [oO];
fragment P : [pP];
fragment Q : [qQ];
fragment R : [rR];
fragment S : [sS];
fragment T : [tT];
fragment U : [uU];
fragment V : [vV];
fragment W : [wW];
fragment X : [xX];
fragment Y : [yY];
fragment Z : [zZ];