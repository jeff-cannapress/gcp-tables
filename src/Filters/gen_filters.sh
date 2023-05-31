#!/bin/sh
java -classpath ./antlr-4.13.0-complete.jar org.antlr.v4.Tool -Dlanguage=PHP -visitor -no-listener TableFilter.g4 -package CannaPress\\GcpTables\\Filters
rm TableFilterBaseVisitor.php
rm *.tokens
rm *.interp