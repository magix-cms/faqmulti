<?php
/**
 * Class plugins_test_core
 * Fichier pour les plugins core
 */
class plugins_faqmulti_core extends plugins_faqmulti_admin
{
    /**
     * @var object
     */
    protected
        $modelPlugins,
        $plugins;

    /**
     * @var int
     */
    public
        $mod_edit;

    /**
     * @var string
     */
    public
        $mod_action,
        $plugin;

    /**
     * plugins_faqmulti_core constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->modelPlugins = new backend_model_plugins();
        $this->plugins = new backend_controller_plugins();
        $formClean = new form_inputEscape();

        if (http_request::isGet('plugin')) $this->plugin = $formClean->simpleClean($_GET['plugin']);
        if (http_request::isRequest('mod_action')) $this->mod_action = $formClean->simpleClean($_REQUEST['mod_action']);
        if (http_request::isGet('mod_edit')) $this->mod_edit = $formClean->numeric($_GET['mod_edit']);
    }
    /**
     *
     */
    protected function runAction()
    {
        switch ($this->mod_action) {
            case 'add':
            case 'edit':
            if( isset($this->content) && !empty($this->content) ) {
                $notify = 'update';
                $revisions = new backend_controller_revisions();

                $currentId = isset($this->content['id']) ? $this->content['id'] : null;
                unset($this->content['id']);

                if (!$currentId) {
                    $this->add([
                        'type' => 'faqmulti',
                        'data' => [
                            'module' => $this->controller,
                            'id_module' => $this->edit ?: NULL
                        ]
                    ]);

                    $lastfaqmulti = $this->getItems('lastfaqmulti', null, 'one', false);
                    $currentId = $lastfaqmulti['id_faqmulti'];
                    $notify = 'add_redirect';
                }

                foreach ($this->content as $lang => $faqmulti) {
                    if (!is_array($faqmulti)) continue;

                    $faqmulti['id_lang'] = $lang;
                    $faqmulti['published_faqmulti'] = (!isset($faqmulti['published_faqmulti']) ? 0 : 1);

                    if (!isset($faqmulti['desc_faqmulti'])) {
                        $faqmulti['desc_faqmulti'] = '';
                    }

                    $faqmultiLang = $this->getItems('faqmultiContent', [
                        'id' => $currentId,
                        'id_lang' => $lang
                    ], 'one', false);

                    if($faqmultiLang) {
                        $faqmulti['id'] = $faqmultiLang['id_faqmulti'];

                        if (!empty($faqmulti['desc_faqmulti'])) {
                            $revisions->saveRevision($this->controller, $faqmulti['id'], $lang, 'desc_faqmulti', $faqmulti['desc_faqmulti']);
                        }
                        $this->upd(['type' => 'faqmultiContent', 'data' => $faqmulti]);
                    } else {
                        $faqmulti['id_faqmulti'] = $currentId;
                        $this->add(['type' => 'faqmultiContent', 'data' => $faqmulti]);
                    }
                }

                $this->message->json_post_response(true, $notify);
            } else {
                    $this->modelLanguage->getLanguage();
                    if(isset($this->mod_edit)) {
                        $collection = $this->getItems('faqmultiContent',$this->mod_edit,'all',false);
                        $setEditData = $this->setItemfaqmultiData($collection);
                        $this->template->assign('faqmulti', $setEditData[$this->mod_edit]);
                    }

                    $this->template->assign('edit',$this->mod_action === 'edit');
                    $this->modelPlugins->display('mod/edit.tpl');
                }
                break;
            case 'delete':
                if(isset($this->id) && !empty($this->id)) {
                    $this->del([
                        'type' => 'faqmulti',
                        'data' => ['id' => $this->id]
                    ]);
                }
                break;
            case 'order':
                if (isset($this->content) && is_array($this->content)) {
                    $this->order('home');
                }
                break;
        }
    }

    /**
     *
     */
    protected function adminList()
    {
        $this->modelLanguage->getLanguage();
        $defaultLanguage = $this->collectionLanguage->fetchData(['context'=>'one','type'=>'default']);
        $this->getItems('faqmultis',['lang' => $defaultLanguage['id_lang'], 'module' => $this->controller, 'id_module' => $this->edit ?: NULL],'all');
        $assign = [
            'id_faqmulti',
            //'url_faqmulti' => ['title' => 'name'],
            //'icon_faqmulti' => ['type' => 'bin', 'input' => null, 'class' => ''],
            'title_faqmulti' => ['title' => 'name'],
            'desc_faqmulti' => ['title' => 'name','type' => 'bin', 'input' => null],
        ];
        $this->data->getScheme(['mc_faqmulti', 'mc_faqmulti_content'], ['id_faqmulti', 'title_faqmulti','desc_faqmulti'], $assign);
        $this->modelPlugins->display('mod/index.tpl');
    }

    /**
     * Execution du plugin dans un ou plusieurs modules core
     */
    public function run() {
        if(isset($this->controller)) {
            switch ($this->controller) {
                case 'about':
                    $extends = $this->controller.(!isset($this->action) ? '/index.tpl' : '/pages/edit.tpl');
                    break;
                case 'category':
                case 'product':
                    $extends = 'catalog/'.$this->controller.'/edit.tpl';
                    break;
                case 'news':
                case 'catalog':
                    $extends = $this->controller.'/index.tpl';
                    break;
                case 'pages':
                case 'home':
                    $extends = $this->controller.'/edit.tpl';
                    break;
                default:
                    $extends = 'index.tpl';
            }
            $this->template->assign('extends',$extends);
            if(isset($this->mod_action)) {
                $this->runAction();
            }
            else {
                $this->adminList();
            }
        }
    }
}