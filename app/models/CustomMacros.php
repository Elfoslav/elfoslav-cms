<?php

namespace Models;

use Nette\Latte\MacroNode,
    Nette\Latte\Macros\MacroSet,
    Nette\Latte\Compiler,
    Nette\Latte\PhpWriter;

class CustomMacros extends MacroSet
{

    public static function install(Compiler $compiler)
    {
        $set = new static($compiler);
        $set->addMacro('id', NULL, NULL, array($set, 'macroId'));
    }


    /**
     * n:id="..."
     */
    public function macroId(MacroNode $node, PhpWriter $writer)
    {
        return $writer->write('if ($_l->tmp = array_filter(%node.array)) echo \' id="\' . %escape(implode(" ", array_unique($_l->tmp))) . \'"\'');
    }

}
