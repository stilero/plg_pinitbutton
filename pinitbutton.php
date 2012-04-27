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
    var $errorOccured;

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
     * Method is called before the content is deleted
     *
     * @var string  $context    The context of the content passed to the plugin
     * @var object  $data       Data relating to the content deleted
     * @return boolean
     * @since 1.6
     */
    public function onContentAfterDelete($context, $data) {
        return true;
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
    public function onContentAfterDisplay($context, &$article, &$params, $limitstart=0) {
        return '';
    }

    /**
     * Method is called right after the content is saved
     *
     * @var string  $context    The context of the content passed to the plugin
     * @var object  $article    JTableContent object
     * @var bool    $isNew      If the content is just about to be created
     * @return void
     * @since 1.6
     */
    public function onContentAfterSave($context, &$article, $isNew) {
        
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
    public function onContentAfterTitle($context, &$article, &$params, $limitstart=0) {
        return '';
    }

    /**
     * Method is called before the content is deleted
     *
     * @var string  $context    The context of the content passed to the plugin
     * @var object  $data       Data relating to the content deleted
     * @return boolean
     * @since 1.6
     */
    public function onContentBeforeDelete($context, $data) {
        return true;
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
        return '';
    }

    /**
     * Method is called right after the content is saved
     *
     * @var string  $context    The context of the content passed to the plugin
     * @var object  $article    JTableContent object
     * @var bool    $isNew      If the content is just about to be created
     * @return boolean          If false, abort the save
     * @since 1.6
     */
    public function onContentBeforeSave($context, &$article, $isNew) {
        return true;
    }

    /**
     * Called after Change state initiated
     *
     * @var string  $context    The context of the content passed to the plugin
     * @var array   $pks        A list of primary key ids of the content that has changed state.
     * @var integer $value      The value of the state that the content has been changed to.
     * @return boolean
     * @since 1.6
     */
    public function onContentChangeState($context, $pks, $value) {
        return true;
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
        
    }

    // ---------- Joomla 1.5 methods ------------------

    /**
     * Method is called right before the content is saved
     *
     * @var object  $article    Reference to JTableContent object
     * @var bool    $isNew      If the content is just about to be created
     * @return boolean          If false, abort the save
     * @since 1.5
     */
    public function onBeforeContentSave(&$article, $isNew) {
        global $mainframe;
        return true;
    }

    /**
     * Method is called right after the content is saved
     *
     * @var object  $article    Reference to JTableContent object
     * @var bool    $isNew      If the content is just about to be created
     * @return boolean          If false, abort the save
     * @since 1.5
     */
    public function onAfterContentSave(&$article, $isNew) {
        global $mainframe;
        return true;
    }

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
    }

    /**
     * This is a request for information that should be placed between the content title and the content body.
     *
     * @var object  $article    A reference to the article that is being rendered by the view.
     * @var array   $params     A reference to an associative array of relevant parameters.
     * @var integer $limitstart An integer that determines the "page" of the content that is to be generated.
     * @return string           Returned value from this event will be displayed in a placeholder.
     * @since 1.5
     */
    public function onAfterDisplayTitle(&$article, &$params, $limitstart=0) {
        global $mainframe;
        return '';
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
        return '';
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
        return '';
    }

}

//End Class