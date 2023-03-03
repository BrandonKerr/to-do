<?php

namespace App\Meta\CodeStyle;

use PhpCsFixer\Fixer\FixerInterface;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;

class QuoteFixer implements FixerInterface {
    /**
     * Check if the fixer is a candidate for given Tokens collection.
     *
     * Fixer is a candidate when the collection contains tokens that may be fixed
     * during fixer work. This could be considered as some kind of bloom filter.
     * When this method returns true then to the Tokens collection may or may not
     * need a fixing, but when this method returns false then the Tokens collection
     * need no fixing for sure.
     */
    public function isCandidate(Tokens $tokens): bool {
        return $tokens->isTokenKindFound(T_CONSTANT_ENCAPSED_STRING);
    }

    /**
     * Check if fixer is risky or not.
     *
     * Risky fixer could change code behavior!
     */
    public function isRisky(): bool {
        return false;
    }

    /**
     * Fixes a file.
     *
     * @param \SplFileInfo $file A \SplFileInfo instance
     * @param Tokens $tokens Tokens collection
     */
    public function fix(\SplFileInfo $file, Tokens $tokens): void {
        /**
         * @var Token $token
         */
        foreach ($tokens as $idx => $token) {
            if (! $token->isGivenKind(T_CONSTANT_ENCAPSED_STRING)) {
                continue;
            }

            $content = $token->getContent();
            if (
                "'" === $content[0] &&
                false === strpos($content, '"') &&
                // regex: odd number of backslashes, not followed by double quote or dollar
                ! preg_match("/(?<!\\\\)(?:\\\\{2})*\\\\(?!['$\\\\])/", $content)
            ) {
                $content = substr($content, 1, -1);
                $content = str_replace("\\'", "'", $content);
                $content = str_replace("$", "$", $content);

                $tokens->offsetSet($idx, new Token('"' . $content . '"'));
            }
        }
    }

    /**
     * Returns the definition of the fixer.
     */
    public function getDefinition(): FixerDefinitionInterface {
        return new FixerDefinition(
            "Converts single quoted simple strings to double quotes",
            [
                new CodeSample(<<<'input'
<?php $foo = ['foo' => 'bar']; ?>
input, ),
            ],
        );
    }

    /**
     * Returns the name of the fixer.
     *
     * The name must be all lowercase and without any spaces.
     *
     * @return string The name of the fixer
     */
    public function getName(): string {
        return "Solgenpower/double_quotes";
    }

    /**
     * Returns the priority of the fixer.
     *
     * The default priority is 0 and higher priorities are executed first.
     */
    public function getPriority(): int {
        return 0;
    }

    /**
     * Returns true if the file is supported by this fixer.
     *
     * @return bool true if the file is supported by this fixer, false otherwise
     */
    public function supports(\SplFileInfo $file): bool {
        return true;
    }
}
