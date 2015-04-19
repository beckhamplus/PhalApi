<?php

class Api_DogstarTest extends PhalApi_Api {

    public function getRules() {
        return array(
            'report' => array(
                'username' => array('name' => 'username', 'require' => true),
                'msg' => array('name' => 'msg', 'require' => true),
            ),
        );
    }

    public function log() {
        //只有描述
        DI()->logger->error('fail to insert DB');

        //描述 + 简单的信息
        DI()->logger->error('fail to insert DB', 'try to register user dogstar');

        //描述 + 当时的上下文数据
        $data = array('name' => 'dogstar', 'password' => '123456');
        DI()->logger->error('fail to insert DB', $data);

        DI()->logger->info('add user exp', array('name' => 'dogstar', 'before' => 10, 'addExp' => 2, 'after' => 12, 'reason' => 'help one more phper'));

        DI()->logger->log('demo', 'add user exp', array('name' => 'dogstar', 'after' => 12));
        DI()->logger->log('test', 'add user exp', array('name' => 'dogstar', 'after' => 12));
    }

    public function report() {
        DI()->logger->info($this->username, $this->msg);
    }

    public function cookieCase1() {
        $rs = array();

        $cookie = new PhalApi_Cookie(array('domain' => '.phalapi.com', 'path' => '/'));
        $rs['aKey'] = $cookie->get('aKey');
        $cookie->set('aKey', 'phalapi', $_SERVER['REQUEST_TIME'] + 600);

        $rs['bKey'] = $cookie->get('bKey');
        $cookie->set('bKey', 1, $_SERVER['REQUEST_TIME'] + 10);
        $cookie->delete('bKey');

        return $rs;
    }

    public function cookieCase2() {
        $rs = array();

        $cookie = new PhalApi_Cookie();
        $rs['aKey'] = $cookie->get('aKey');
        $cookie->set('aKey', 'phalapi', $_SERVER['REQUEST_TIME'] + 600);

        $rs['bKey'] = $cookie->get('bKey');
        $cookie->set('bKey', 1, $_SERVER['REQUEST_TIME'] + 10);
        $cookie->delete('bKey');

        return $rs;
    }

    public function cookieCase3() {
        $rs = array();

        $config = array('crypt' => new Cookie_Crypt_Mock(), 'key' => 'a secrect');
        $cookie = new PhalApi_Cookie_Multi($config);
        $rs['aEKey'] = $cookie->get('aEKey');
        $cookie->set('aEKey', 'phalapi', $_SERVER['REQUEST_TIME'] + 600);

        $rs['bEKey'] = $cookie->get('bEKey');
        $cookie->set('bEKey', 1, $_SERVER['REQUEST_TIME'] + 10);
        $cookie->delete('bKey');

        return $rs;

    }

    public function cookieTest() {
        $rs = array();

        $config = array('crypt' => new Cookie_Crypt_Mock(), 'key' => 'a secrect');
        DI()->cookie = new PhalApi_Cookie_Multi($config);

        $rs['aEKey'] = DI()->cookie->get('name');
        DI()->cookie->set('name', 'phalapi', $_SERVER['REQUEST_TIME'] + 600);

        return $rs;

    }
}

class Cookie_Crypt_Mock implements PhalApi_Crypt {

    public function encrypt($data, $key) {
        return base64_encode($data);
    }

    public function decrypt($data, $key) {
        return base64_decode($data);
    }
}

