<?php

/**
 * Description of DsNoty
 *
 * @author satitseethaphon
 */
class DsNotify extends CApplicationComponent {

    const TYPE_ALERT = 'alert';
    const TYPE_ERROR = 'error';
    const TYPE_SUCCESS = 'success';
    const TYPE_WARNING = 'warning';
    const TYPE_INFO = 'information';
    
    const LAYOUT_TOP = 'top';
    
    const LAYOUT_TOPLEFT = 'topLeft';
    const LAYOUT_TOPCENTER = 'topCenter';
    const LAYOUT_TOPRIGHT = 'topRight';
    
    const LAYOUT_CENTERLEFT = 'centerLeft';
    const LAYOUT_CENTER = 'center';
    const LAYOUT_CENTERRIGHT = 'centerRight';
    
    const LAYOUT_BOTTOMLEFT = 'bottomLeft';
    const LAYOUT_BOOTTOMCENTER = 'bottomCenter';
    const LAYOUT_BOTTOMRIGHT = 'bottomRight';
    
    const LAYOUT_BOOTTOM = 'bottom';
    
    const THEMES_DEFAULT = 'default';

    public $theme = self::THEMES_DEFAULT;
    protected $_assetsUrl;
    public $layout = self::LAYOUT_TOPRIGHT;
    public $timeout = 3000;

    public function init() {
        $this->setRootAliasIfUndefined();
        $this->registerJs();
        $this->registerLayout();
        $this->registerTheme();
    }

    protected function setRootAliasIfUndefined() {
        if (Yii::getPathOfAlias('dsnotify') === false) {
            Yii::setPathOfAlias('dsnotify', realpath(dirname(__FILE__) . '/../dsnotify/'));
        }
    }

    public function noty($text = 'Notify Message', $type = self::TYPE_ALERT, $layout = NULL) {

        if ($layout != NULL) {
            $this->setLayout($layout);
            $this->registerLayout();
        }

        $js = "
       noty({
  		text: '{$text}',
  		type: '{$type}',
                layout:'{$this->layout}',
                timeout:'{$this->timeout}',
                dismissQueue: true,
  		theme: 'defaultTheme'
  	});";
        Yii::app()->clientScript->registerScript(__CLASS__ . 'dsnotify-' . rand(1, 100), $js, CClientScript::POS_READY);
    }

    protected function registerJs() {
        Yii::app()->clientScript->registerScriptFile($this->getAssetsUrl() . '/jquery.noty.js', CClientScript::POS_END);
    }

    protected function registerLayout() {
        Yii::app()->clientScript->registerScriptFile($this->getAssetsUrl() . '/layouts/' . $this->layout . '.js', CClientScript::POS_END);
    }

    protected function registerTheme() {
        Yii::app()->clientScript->registerScriptFile($this->getAssetsUrl() . '/themes/' . $this->theme . '.js', CClientScript::POS_END);
    }

    protected function setLayout($layout = self::LAYOUT_BOTTOMRIGHT) {
        if ($this->checkLayout($layout))
            $this->layout = $layout;
        else
            throw new CHttpException(404, "The DSNoty extension requested layout {$layout} does not exist.");
    }

    protected function checkLayout($layout) {
        return in_array($layout, array(
            self::LAYOUT_BOOTTOM,
            self::LAYOUT_BOTTOMLEFT,
            self::LAYOUT_BOOTTOMCENTER,
            self::LAYOUT_BOTTOMRIGHT,
            self::LAYOUT_CENTERLEFT,
            self::LAYOUT_CENTER,
            self::LAYOUT_CENTERRIGHT,
            self::LAYOUT_TOP,
            self::LAYOUT_TOPLEFT,
            self::LAYOUT_TOPCENTER,
            self::LAYOUT_TOPRIGHT
        ));
    }

    protected function getAssetsUrl() {
        if ($this->_assetsUrl !== null)
            return $this->_assetsUrl;
        else {
            $assetsPath = Yii::getPathOfAlias('dsnotify.assets');

            if (YII_DEBUG)
                $assetsUrl = Yii::app()->assetManager->publish($assetsPath, false, -1, true);
            else
                $assetsUrl = Yii::app()->assetManager->publish($assetsPath);

            return $this->_assetsUrl = $assetsUrl;
        }
    }

}
