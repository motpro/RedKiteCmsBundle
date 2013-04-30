<?php

/*
 * This file is part of the AlphaLemon CMS Application and it is distributed
 * under the GPL LICENSE Version 2.0. To use this application you must leave
 * intact this copyright notice.
 *
 * Copyright (c) AlphaLemon <webmaster@alphalemon.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * For extra documentation and help please visit http://www.alphalemon.com
 *
 * @license    GPL LICENSE Version 2.0
 *
 */

namespace AlphaLemon\AlphaLemonCmsBundle\Core\Deploy\TwigTemplateWriter;

use AlphaLemon\AlphaLemonCmsBundle\Core\PageTree\AlPageTree;
use AlphaLemon\AlphaLemonCmsBundle\Core\UrlManager\AlUrlManagerInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\Content\Block\AlBlockManagerFactoryInterface;
use AlphaLemon\AlphaLemonCmsBundle\Core\ViewRenderer\AlViewRendererInterface;

/**
 * AlTwigTemplateWriter generates a twig template from a PageTree object
 *
 * @author AlphaLemon <webmaster@alphalemon.com>
 * 
 * @api
 */
class AlTwigTemplateWriterBase extends AlTwigTemplateWriter
{

    public function writeTemplate($dir)
    {
        // Writes down the file
        $fileDir = $dir . '/' . $this->pageTree->getAlLanguage()->getLanguageName() . '/base';
        if (!is_dir($fileDir)) {
            mkdir($fileDir);
        }

        return @file_put_contents($fileDir . '/' . $this->template->getTemplateName() . '.html.twig', $this->twigTemplate);
    }

    /**
     * Generates the template's subsections and the full template itself
     */
    protected function generateTemplate()
    {
        $this->generateTemplateSection();
        $this->generateContentsSection(array('site', 'language'));
        $this->generateAddictionalMetaTagsSection();
       
        $this->twigTemplate = $this->templateSection . $this->metatagsExtraSection . $this->contentsSection;
    }
    
    /**
     * Generates the template extension section
     */
    protected function generateTemplateSection()
    {
        $this->templateSection = sprintf("{%% extends '%s:Theme:%s.html.twig' %%}" . PHP_EOL, $this->template->getThemeName(), $this->template->getTemplateName());
    }
}
