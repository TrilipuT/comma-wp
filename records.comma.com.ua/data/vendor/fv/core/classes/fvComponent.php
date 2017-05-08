<?php


abstract class fvComponent {

    private $templateDir = null;
    private $templateName = null;
    private $content = null;

    /** @var fvView */
    private $view;

    /**
     * Render Component with some template
     * @return Strings HTML code
     **/
    final public function render() {
        if( $this->content === null ){
            $this->assignVars();
            $this->view()->this = $this;

            $extension = $this->view()->getExtension();

            $finder = new fvTemplateFinder( $this, $this->view()->getExtension() );

            $this->view()
                ->setPath( $finder->getPath() )
                ->setView( $finder->getFileName() );

            $this->setContent( $this->view()->render() );
        }

        return $this->content;
    }

    final protected function setContent( $content ){
        $this->content = $content;
        return $this;
    }

    final public function clearContent(){
        $this->content = null;
        return $this;
    }

    /**
     * @return fvView
     */
    protected function createView(){
        $class = "View_" . fvSite::config()->get("view.class", "Twig");
        $view = new $class();
        return $view;
    }

    /**
     * @return fvView
     */
    final protected function view(){
        if( !isset($this->view) )
            $this->view = $this->createView();

        return $this->view;
    }

    function prerender(){
        $this->render();
        return $this;
    }

    final public function __toString(){
        try{
            return $this->render();
        } catch( Exception $e ){
            if( !FV_DEBUG_MODE || !defined("FV_DEBUG_MODE") )
                return "Error while render «" . get_class() . "» Component.";

            return Strings::parseException( $e );
        }
    }

    /**
     * @param Strings $templateDir
     *
     * @return $this
     */
    public function setTemplateDir( $templateDir )
    {
        $this->content = null;
        $this->templateDir = $templateDir;
        return $this;
    }

    /**
     * @return Strings
     */
    public function getTemplateDir()
    {
        return $this->templateDir;
    }

    /**
     * @param Strings $templateName
     *
     * @return $this
     */
    public function setTemplateName( $templateName )
    {
        $this->content = null;
        $this->templateName = $templateName;
        return $this;
    }

    /**
     * @return Strings
     */
    public function getTemplateName()
    {
        return $this->templateName;
    }

    public function getComponentName(){
        return "component";
    }

    protected function assignVars() {}

}
