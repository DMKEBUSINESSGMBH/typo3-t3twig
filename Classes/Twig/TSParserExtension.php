<?php

namespace DMK\T3twig\Twig;

use DMK\T3twig\Util\T3twigEnvironment;

/**
 * Class TSParserExtension
 *
 * @category TYPO3-Extension
 * @package  DMK\T3twig\Twig
 * @author   Eric Hertwig <dev@dmk-ebusiness.de>
 * @author   Michael Wagner <dev@dmk-ebusiness.de>
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://www.dmk-ebusiness.de/
 */
class TSParserExtension extends \Twig_Extension
{
    /**
     * @return array
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter(
                't3parseField', [$this, 'parseField'],
                ['needs_environment' => true, 'is_safe' => ['html'],]
            ),
        ];
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction(
                't3cObject', [$this, 'renderContentObject'],
                ['needs_environment' => true, 'is_safe' => ['html']]
            ),
        ];
    }

    /**
     * @param T3twigEnvironment $env
     * @param array             $record
     * @param string            $field
     *
     * @return string
     */
    public function parseField(T3twigEnvironment $env, $record, $field)
    {
        $configurations = $env->getConfigurations();
        $confId         = $env->getConfId();
        $conf           = $configurations->get($confId);
        $cObj           = $configurations->getCObj();
        $tmp            = $cObj->data;
        $value          = $record[ $field ];

        $cObj->data = $record;

        // For DATETIME there is a special treatment to treat empty values
        if ($conf[ $field ] == 'DATETIME' && $conf[ $field.'.' ]['ifEmpty'] && !$value) {
            $value = $conf[ $field.'.' ]['ifEmpty'];
        } elseif ($conf[ $field ]) {
            $cObj->setCurrentVal($value);
            $value = $cObj->cObjGetSingle($conf[ $field ], $conf[ $field.'.' ]);
            $cObj->setCurrentVal(false);
        } elseif ($conf[ $field ] == 'CASE') {
            $value = $cObj->CASEFUNC($conf[ $field.'.' ]);
        } else {
            $value = $cObj->stdWrap($value, $conf[ $field.'.' ]);
        }

        $cObj->data = $tmp;

        return $value;
    }

    /**
     * Creates output based on TypoScript.
     *
     * @param T3twigEnvironment $env
     * @param string            $typoscriptObjectPath
     * @param array             $data
     *
     * @throws \Exception
     *
     * @return string
     */
    public function renderContentObject(
        T3twigEnvironment $env,
        $typoscriptObjectPath,
        $data = null
    ) {
        $currentValue = null;
        if (is_scalar($data)) {
            $currentValue = (string)$data;
            $data         = [$data];
        }
        // @TODO: handle objects!

        $contentObject = $env->getConfigurations()->getCObj();

        // set data
        if ($data !== null) {
            $backupData          = $contentObject->data;
            $contentObject->data = $data;
        }

        if ($currentValue !== null) {
            $contentObject->setCurrentVal($currentValue);
        }

        $setup = $GLOBALS['TSFE']->tmpl->setup;

        $pathSegments = \Tx_Rnbase_Utility_Strings::trimExplode(
            '.',
            $typoscriptObjectPath
        );
        $lastSegment  = array_pop($pathSegments);

        // check the ts path and find the setup config
        foreach ($pathSegments as $segment) {
            if (!array_key_exists(($segment.'.'), $setup)) {
                throw new \Exception(
                    'TypoScript object path "'.htmlspecialchars($typoscriptObjectPath).'" does not exist',
                    1483710972
                );
            }
            $setup = $setup[ $segment.'.' ];
        }

        // render the ts
        $content = $contentObject->cObjGetSingle(
            $setup[ $lastSegment ],
            $setup[ $lastSegment.'.' ]
        );

        // reset data
        if (isset($backupData)) {
            $contentObject->data = $backupData;
        }

        return $content;
    }

    /**
     * Get Extension name
     *
     * @return string
     */
    public function getName()
    {
        return 't3twig_tsParserExtension';
    }
}
