<?php

/**
 * Description of PinitButton
 *
 * @version  1.3
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
   var $classNames;
    var $Article;
    var $debug;

    function plgContentPinitbutton(&$subject, $config) {
        parent::__construct($subject, $config);
        $language = JFactory::getLanguage();
        $language->load('plg_content_pinitbutton', JPATH_ADMINISTRATOR, 'en-GB', true);
        $language->load('plg_content_pinitbutton', JPATH_ADMINISTRATOR, null, true);
        $this->errorOccured = FALSE;
        $this->classNames = array(
            'com_article'       =>  'PinBtnJArticle',
            'com_content'       =>  'PinBtnJArticle',
            'com_k2'            =>  'PinBtnK2Article',
            'com_zoo'           =>  'PinBtnZooArticle',
            'com_virtuemart'    =>  'PinBtnVmArticle'
        );
        $this->debug = FALSE;
        
    }

     // ---------- Joomla 1.6+ methods ------------------
    public function onContentPrepare($context, &$article, &$params, $limitstart=0) {
        if( $context != 'com_content.article' && $context !='com_virtuemart.productdetails'){
            return;
        }
        if($this->params->def('placement')!='0' ){
            return;
        }
        if(!$this->loadClasses($article)){
            return;
        }
        $this->insertButtonScriptDeclaration();
//        $regex = '/{pinitbtn}/i';
//        preg_replace($regex, $this->buttonScript(), $article->text);
        $article->text = $this->replaceWildcardInContent($article->text);
    }
    
    public function onK2PrepareContent(&$item, &$params, $limitstart=0) {
        if(JRequest::getVar('option')!='com_k2' || JRequest::getVar('view')!='item'){
            return;
        }
        if($this->params->def('placement')!='0' ){
            return;
        }
        if(!$this->loadClasses($item)){
            return;
        }
        $this->insertButtonScriptDeclaration();
        $item->text = $this->replaceWildcardInContent($item->text);
    }
    
    public function onContentAfterDisplay($context, &$article, &$params, $limitstart=0) {

        if( $context != 'com_content.article' && $context !='com_virtuemart.productdetails'){
            return;
        }
        if($this->params->def('placement')!='2' || ( !$this->isArticleContext() && !$this->isProductContext() ) ){
            return '';
        }
        return $this->buttonScript();
    }

    public function onContentBeforeDisplay($context, &$article, &$params, $limitstart=0) {
        //if(JRequest::getVar('option')!='com_content' || JRequest::getVar('view')!='article'){
        if( $context != 'com_content.article' && $context !='com_virtuemart.productdetails'){
            return;
        }
        if($this->params->def('placement')!='1' || ( !$this->isArticleContext() && !$this->isProductContext() ) ){
            return '';
        }
        return $this->buttonScript();
    }

    // ---------- Joomla 1.5 methods ------------------
    public function onPrepareContent(&$article, &$params, $limitstart=0) {
        global $mainframe;
        if( $context != 'com_content.article' && $context !='com_virtuemart.productdetails'){
                return;
        }
        if(!$this->loadClasses($article)){
            return;
        }
        $this->insertButtonScriptDeclaration();
//        $regex = '/{pinitbtn}/i';
//        preg_replace($regex, $this->buttonScript(), $article->text);
        $article->text = $this->replaceWildcardInContent($article->text);
    }

    public function onBeforeDisplayContent(&$article, &$params, $limitstart=0) {
        global $mainframe;
        if(JRequest::getVar('option') != 'com_content' || JRequest::getVar('view') != 'article'){
            return;
        }
        if( $this->params->def('placement')!='1' || ( !$this->isArticleContext() && !$this->isProductContext() ) ){
            return '';
        }
        return $this->buttonScript();
    }
    
    public function onAfterDisplayContent(&$article, &$params, $limitstart=0) {
        global $mainframe;
        if(JRequest::getVar('option') != 'com_content' || JRequest::getVar('view') != 'article'){
            return;
        }
        if($this->params->def('placement')!='2' || ( !$this->isArticleContext() && !$this->isProductContext() ) ){
            return '';
        }
        return $this->buttonScript();
    }
    
    // ---------------- K2 Methods ------------------------
    public function onK2AfterDisplayContent(& $item, &$params, $limitstart=0){
       if(JRequest::getVar('option')!='com_k2' || JRequest::getVar('view')!='item'){
            return;
        }
        if($this->params->def('placement')!='2' || !$this->isArticleContext(TRUE) ){
            return;
        }
        if(!$this->loadClasses($item)){
            return;
        }
        $this->insertButtonScriptDeclaration();
        return $this->buttonScript();
   }
   
   public function onK2BeforeDisplayContent(& $item, &$params, $limitstart=0){
       if(JRequest::getVar('option')!='com_k2' || JRequest::getVar('view')!='item'){
            return;
        }
        if($this->params->def('placement')!='1' || !$this->isArticleContext(TRUE) ){
            return;
        }
        if(!$this->loadClasses($item)){
            return;
        }
        $this->insertButtonScriptDeclaration();
        return $this->buttonScript();
   }
   
   //------------------ Custom methods ---------------------
   private function loadClasses($article){
       $component = JRequest::getVar('option');
        if(array_key_exists($component, $this->classNames)){
            $className = $this->classNames[$component];
            JLoader::register( $className, dirname(__FILE__).DS.'pinterestclasses'.DS.'jArticle.php');
            $articleFactory = new $className($article);
            $this->Article = $articleFactory->getArticleObj();
            if($this->debug == true){
                JError::raiseNotice('0', 'Class; '.$className);
                JError::raiseNotice('0', var_dump($this->Article));
            }
            return TRUE;
        }
        return false;
   }
   
    private function isArticleContext($isK2=false){
        $viewName = 'article';
        if($isK2){
            $viewName = 'item';
        }
        $isArticleView = JRequest::getVar('view') == $viewName ? true : false;
        $hasArticleID = JRequest::getVar('id') != '' ? true : false;
        if($isArticleView && $hasArticleID){
            return true;
        }else{
            return false;
        }
    }
    
    private function isProductContext(){
        $productID = JRequest::getVar('product_id');
        if($productID == ''){
            $productID = JRequest::getVar('virtuemart_product_id');
        }
        $isProduct = $productID != '' ? true : false;
        return $isProduct;
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
        $url = urlencode($this->Article->url);
        $imageurl = urlencode($this->findBestImage());
        $desc = urlencode($this->description());
        $buttonImg = $this->buttonImage();
        $layout = $this->layoutAsAttr();
        $buttonScript = '<div class="pinitButton"><a href="http://pinterest.com/pin/create/button/?url='.$url.'&media='.$imageurl.'&description='.$desc.'" class="pin-it-button"'.$layout.'><img border="0" src="'.$buttonImg.'" title="Pin It" /></a></div>';
        //if($this->debug) JError::raiseNotice('0', htmlentities ($buttonScript));
        return $buttonScript;
    }
    
    public function insertButtonScriptDeclaration(){
        $document = JFactory::getDocument();
        $document->addScript('//assets.pinterest.com/js/pinit.js');
    }
    
    private function description(){
        $desc = $this->Article->description != '' ? $this->Article->description : $desc;
         if($this->debug){
                JError::raiseNotice('0', 'desc: '. $desc);
         }
        switch ($this->params->def('og-desc')) {
            case 1:
                $metadesc = htmlentities(strip_tags($this->Article->metadesc));
                $desc = $metadesc == '' ? $desc : $metadesc ;
                break;
            case 2:
                $introtext = htmlentities(strip_tags($this->Article->introtext));
                $desc = $introtext == '' ? $this->Article->description : $introtext ;
                break;
            case 3:
                $joomlaConfig = JFactory::getConfig();
                $joomlaSiteDesc = htmlentities(strip_tags($joomlaConfig->getValue( 'config.MetaDesc' )));
                $desc = $joomlaSiteDesc == '' ? $desc : $joomlaSiteDesc ;
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
        if($image == "" ){
            $image = $this->image($this->Article->image);
        }
        if($image != ""){
            return htmlentities(strip_tags($image));
        }
    }
    
    private function image($option){
        $image = $this->Article->image;
        if($this->debug){
            JError::raiseNotice('0', 'image; '.$image);
            JError::raiseNotice('0', 'article; '.  var_dump($this->Article));
        }
        switch ($option) {
            case 1:
                if(isset($this->Article->firstContentImage)){
                    $image = ($this->Article->firstContentImage != '') ? $this->Article->firstContentImage : $image;
                }
                break;
            case 2:
                if(isset($this->Article->introImage)){
                    $image = ($this->Article->introImage != '') ? $this->Article->introImage : $image;
                }
                break;
            case 3:
                if(isset($this->Article->fullTextImage)){
                    $image = ($this->Article->fullTextImage != '') ? $this->Article->fullTextImage : $image;
                }
                break;
            case 4:
                $images = (!empty($this->Article->imageArray)) ? $this->Article->imageArray : array($image);
                $cssClass = $this->params->def('og-img-class');
                $classImage = $this->imageWithClass($images, $cssClass);
                if(isset($classImage)){
                    $image = ($classImage != '') ? $classImage : $image;
                }
                break;
            case 5:
                if($image == '' && $this->params->def('og-img-custom') != ''){
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
    
    protected function replaceWildcardInContent($text){
        $newText = str_replace('{pinitbtn}', $this->buttonScript(), $text);
        return $newText;
    }

}
//End Class