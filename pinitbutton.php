<?php

/**
 * Description of PinitButton
 *
 * @version  1.0
 * @author Daniel Eliasson (joomla at stilero.com)
 * @copyright  (C) 2012-apr-27 Stilero Webdesign http://www.stilero.com
 * @category Plugins
 * @license    GPLv2
 *
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 *
 * This file is part of pinitbutton.
 *
 * PinitButton is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PinitButton is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PinitButton.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

// import library dependencies
jimport('joomla.plugin.plugin');

class plgContentPinitbutton extends JPlugin {

   var $config;
    var $Article;

    function plgContentPinitbutton(&$subject, $config) {
        parent::__construct($subject, $config);
        $language = JFactory::getLanguage();
        $language->load('plg_content_pinitbutton', JPATH_ADMINISTRATOR, 'en-GB', true);
        $language->load('plg_content_pinitbutton', JPATH_ADMINISTRATOR, null, true);
        $this->errorOccured = FALSE;
        $this->config = array(
            'key' => 'value',
        );
    }

     // ---------- Joomla 1.6+ methods ------------------

    /**
     * Method is called by the view and the results are imploded and displayed in a placeholder
     *
     * @var string  $context    The context of the content passed to the plugin
     * @var object  $article    content object. Note $article->text is also available
     * @var object  $params     content params
     * @var integer $limitstart The 'page' number
     * @return String
     * @since 1.6
     */
    public function onContentAfterDisplay($context, &$article, &$params, $limitstart=0) {
        if($this->params->def('placement')!='2' || !$this->isArticleContext() ){
            return '';
        }
        return $this->buttonScript();
    }

    /**
     * Method is called by the view and the results are imploded and displayed in a placeholder
     *
     * @var string  $context    The context of the content passed to the plugin
     * @var object  $article    content object. Note $article->text is also available
     * @var object  $params     content params
     * @var integer $limitstart The 'page' number
     * @return String
     * @since 1.6
     */
    public function onContentBeforeDisplay($context, &$article, &$params, $limitstart=0) {
        if($this->params->def('placement')!='1' || !$this->isArticleContext() ){
            return '';
        }
        return $this->buttonScript();
    }

    /**
     * Method is called by the view
     *
     * @var string  $context    The context of the content passed to the plugin
     * @var object  $article    content object. Note $article->text is also available
     * @var object  $params     content params
     * @var integer $limitstart The 'page' number
     * @return void
     * @since 1.6
     */
    public function onContentPrepare($context, &$article, &$params, $limitstart=0) {
        JLoader::register( 'jArticle', dirname(__FILE__).DS.'pinterestclasses'.DS.'jArticle.php');
        if($context!='com_content.article'){
            return;
        }
        $articleFactory = new jArticle($article);
        $this->Article = $articleFactory->getArticleObj();
        $this->insertButtonScriptDeclaration();
        $regex = '/{pinitbtn}/i';
        preg_replace($regex, $this->buttonScript(), $article->text);
    }

    // ---------- Joomla 1.5 methods ------------------

    /**
     * The first stage in preparing content for output and is the most common point for content orientated plugins to do their work.
     *
     * @var object  $article    A reference to the article that is being rendered by the view.
     * @var array   $params     A reference to an associative array of relevant parameters.
     * @var integer $limitstart An integer that determines the "page" of the content that is to be generated.
     * @return void
     * @since 1.5
     */
    public function onPrepareContent(&$article, &$params, $limitstart=0) {
        global $mainframe;
        JLoader::register( 'jArticle', dirname(__FILE__).DS.'pinterestclasses'.DS.'jArticle.php');
        $articleFactory = new jArticle($article);
        $this->Article = $articleFactory->getArticleObj();
        $this->insertButtonScriptDeclaration();
        $regex = '/{pinitbtn}/i';
        preg_replace($regex, $this->buttonScript(), $article->text);
    }

    /**
     * This is a request for information that should be placed immediately before the generated content.
     *
     * @var object  $article    A reference to the article that is being rendered by the view.
     * @var array   $params     A reference to an associative array of relevant parameters.
     * @var integer $limitstart An integer that determines the "page" of the content that is to be generated.
     * @return string           Returned value from this event will be displayed in a placeholder.
     * @since 1.5
     */
    public function onBeforeDisplayContent(&$article, &$params, $limitstart=0) {
        global $mainframe;
        if( $this->params->def('placement')!='1' || !$this->isArticleContext() ){
            return '';
        }
        return $this->buttonScript();
    }

    /**
     * This is a request for information that should be placed immediately after the generated content.
     *
     * @var object  $article    A reference to the article that is being rendered by the view.
     * @var array   $params     A reference to an associative array of relevant parameters.
     * @var integer $limitstart An integer that determines the "page" of the content that is to be generated.
     * @return string           Returned value from this event will be displayed in a placeholder.
     * @since 1.5
     */
    public function onAfterDisplayContent(&$article, &$params, $limitstart=0) {
        global $mainframe;
        if($this->params->def('placement')!='2' || !$this->isArticleContext() ){
            return '';
        }
        return $this->buttonScript();
    }
    
    private function isArticleContext(){
        $articleID = JRequest::getVar('id');
        $isArticle = isset ($articleID) ? true : false;
        return $isArticle;
    }
    
    private function layoutAsAttr(){
        $layout = '';
        switch ($this->params->def('pincount')) {
            case 1:
                $layout = ' count-layout="horizontal"';
                break;
            case 2:
                $layout = ' count-layout="vertical"';
                break;
            default:
                $layout = ' count-layout="none"';
                break;
        }
        return $layout;
    }
    
    private function buttonImage(){
        $layout = '';
        switch ($this->params->def('image')) {
            case 1:
                $layout = '//assets.pinterest.com/images/PinExt.png';
                break;
            case 2:
                $layout = 'http://passets-cdn.pinterest.com/images/about/buttons/big-p-button.png';
                break;
            case 3:
                $layout = 'http://passets-cdn.pinterest.com/images/about/buttons/pinterest-button.png';
                break;
            default:
                $layout = '//assets.pinterest.com/images/PinExt.png';
                break;
        }
        return $layout;
    }
    
    private function buttonScript(){
        $url = htmlentities($this->Article->url);
        $imageurl = htmlentities($this->findBestImage());
        $desc = htmlentities($this->description());
        $buttonImg = $this->buttonImage();
        $layout = $this->layoutAsAttr();
        $buttonScript = '<a href="http://pinterest.com/pin/create/button/?url='.$url.'&media='.$imageurl.'&description='.$desc.'" class="pin-it-button"'.$layout.'><img border="0" src="'.$buttonImg.'" title="Pin It" /></a>';
        return $buttonScript;
    }
    
    public function insertButtonScriptDeclaration(){
        $document = JFactory::getDocument();
        $document->addScript('//assets.pinterest.com/js/pinit.js');
    }
    
    private function description(){
        $desc = '';
        switch ($this->params->def('og-desc')) {
            case 1:
                $desc = htmlentities(strip_tags($this->Article->metadesc));
                break;
            case 2:
                $desc = htmlentities(strip_tags($this->Article->introtext));
                break;
            case 3:
                $joomlaConfig = JFactory::getConfig();
                $joomlaSiteName = $joomlaConfig->getValue( 'config.MetaDesc' );
                $desc = htmlentities(strip_tags($joomlaSiteName));
                break;
            case 4:
                $desc = htmlentities(strip_tags($this->params->def('og-desc-custom')));
                break;
            default:
                break;
        }
        $desc = $desc=='' ? htmlentities(strip_tags($this->params->def('og-desc-custom'))) : $desc;
        return $desc;
    }
    
    private function findBestImage(){
        $image = $this->image($this->params->def('og-img-prio1'));
        if($image == "" ){
            $image = $this->image($this->params->def('og-img-prio2'));
        }
        if($image == "" ){
            $image = $this->image($this->params->def('og-img-prio3'));
        }
        if($image != ""){
            return htmlentities(strip_tags($image));
        }
    }
    
    private function image($option){
        $image = '';
        switch ($option) {
            case 1:
                $image = (isset($this->Article->firstContentImage)) ? $this->Article->firstContentImage : '';
                break;
            case 2:
                $image = (isset($this->Article->introImage)) ? $this->Article->introImage : '';
                break;
            case 3:
                $image = (isset($this->Article->fullTextImage)) ? $this->Article->fullTextImage : '';
                break;
            case 4:
                $images = (!empty($this->Article->imageArray)) ? $this->Article->imageArray : null;
                $cssClass = $this->params->def('og-img-class');
                $classImage = $this->imageWithClass($images, $cssClass);
                $image = (isset($classImage)) ? $classImage : '';
                break;
            case 5:
                if($this->params->def('og-img-custom') != ''){
                    $image = 'images/'.$this->params->def('og-img-custom');
                }
                break;
            default:
                return;
                break;
        }
        $image = $image == '' ? 'images/'.$this->params->def('og-img-custom') : $image;
        if($image != ""){
            $image = preg_match('/http/', $image)? $image : JURI::root().$image;
            return $image;
        }
    }
    
    private function imageWithClass($images, $cssClass){
        if( (!isset($images)) || (empty ($images))  ){
            return;
        }
        foreach ($images as $image) {
            if($image['class'] == $cssClass){
                return $image['src'];
            }
        }
    }

}
//End Class