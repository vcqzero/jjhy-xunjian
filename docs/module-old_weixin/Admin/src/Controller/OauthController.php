<?php
/**
 * @insruction：
 *
 * @fileName  :OauthController.php
 * @author: 秦崇
 */
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Admin\Service\OAuthManager;

class OauthController extends AbstractActionController
{

    private $dbAdapter      = null;
    private $mySession     = null;
    private $oauthManager   = null;

    public function __construct(OAuthManager $oauth, $mySession)
    {
        $this->mySession       = $mySession;
        $this->oauthManager     = $oauth;
    }

    // get user code
    public function indexAction()
    {
        $code   = $this->params()->fromQuery('code');
        $state  = $this->params()->fromQuery('state');
        $openid = $this->oauthManager->getOpenId($code);
        
        $this   ->mySession->openid = $openid;
        $data   = explode('0', $state);
        return $this->redirect()->toRoute($data[0], ['action' => $data[1] ]);
    }
}
