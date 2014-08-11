<?php

use app\models\UserTable;
use app\models\User;

/**
*    UserTableTest
*/
class UserTableTest extends PHPUnit_Framework_TestCase
{
    protected $userTable;

    public function setUp()
    {
        
        // get the db adapter and set the userTable object
        
        $dbAdapter = new framework\Db\DatabaseAdapter(array(
            'dsn' => $GLOBALS['DB_DSN'],
            'user' => $GLOBALS['DB_USER'],
            'password' => $GLOBALS['DB_PASSWD'],
        ));

        $this->userTable = new UserTable($dbAdapter);
        
        // clean table on every test so we start clean
        $this->userTable->delete(array('id <> 999999999'));
    }
    
    public function testCreateUser()
    {
        // get default values for new user
        $values = $this->getUserValues();
        
        // count number of records before create
        $before = count($this->userTable->fetchAll());
        
        // assert user is created
        $result = $this->userTable->create($values);
        $this->assertTrue($result);
        
        // check users has been incremented
        $after = count($this->userTable->fetchAll());
        $this->assertGreaterThan($before, $after);
    }
    
    /**
    * @depends testCreateUser
    */
    public function testCreateUserDoesntInsertDuplicateEmail()
    {
        // new user to be created
        $values = $this->getUserValues();
        
        // assert user is created
        $result = $this->userTable->create($values);
        $this->assertTrue($result);
        
        // try again with same email (should be false)
        $result = $this->userTable->create($values);
        $this->assertFalse($result);
    }

    /**
    * @depends testCreateUser
    */
    public function testFetchUserByCriteria()
    {
        // create new user
        $values = $this->getUserValues();
        $user = $this->createAndFetchUser($values);

        $this->assertEquals($user['email'], $values['email']);
    }

    /**
    * @depends testCreateUser
    * @depends testFetchUserByCriteria
    */
    public function testFindUserByPrimaryKey()
    {
        // create new user
        $values = $this->getUserValues();
        $user = $this->createAndFetchUser($values);
        
        // retreive by pk
        $foundUser = $this->userTable->find($user['id']);

        $this->assertEquals($user['id'], $foundUser['id']);
    }
    
    /**
    * @depends testCreateUser
    * @depends testFetchUserByCriteria
    */
    public function testCreateUserAutomaticallySetsCreatedAndUpdatedDatetime()
    {
        // new user to be created
        $values = $this->getUserValues(array(
            'date_created' => null,
            'date_updated' => null,
        ));
        $user = $this->createAndFetchUser($values);
        
        $this->assertTrue(! empty($user['date_created']));
        $this->assertTrue(! empty($user['date_updated']));
    }

    /**
    * @depends testCreateUser
    * @depends testFetchUserByCriteria
    */
    public function testUpdateUserByCriteria()
    {
        // new user to be created
        $values = $this->getUserValues();
        $user = $this->createAndFetchUser($values);
        
        // attempt to update row
        
        $values = array('email' => 'updated@hotmail.com');
        $where = array('id = ?', array($user['id']));
        $result = $this->userTable->update($values, $where);
        
        $this->assertTrue($result);
        
        // fetch row and verify new column value
        
        $user = $this->userTable->fetch($where, array(
            'limitMax' => 1,
        ))[0];
        
        $this->assertEquals($user['email'], $values['email']);
    }

    /**
    * @depends testCreateUser
    * @depends testUpdateUserByCriteria
    * @depends testFetchUserByCriteria
    */
    public function testUpdateUserAutomaticallySetsUpdatedDatetime()
    {
        // create user to retreive
        $values = $this->getUserValues();
        $user = $this->createAndFetchUser($values);
        
        sleep(1); // delay so that dates will be different
        
        // attempt to update row
        
        $values = array('email' => 'updated@hotmail.com');
        $where = array('id = ?', array($user['id']));
        $result = $this->userTable->update($values, $where);
        
        // fetch row and check the dates
        $user = $this->userTable->fetch($where, array(
            'limitMax' => 1,
        ))[0];
        
        $this->assertGreaterThan($user['date_created'], $user['date_updated']);
    }

    /**
    * @depends testCreateUser
    * @depends testFetchUserByCriteria
    */
    public function testDeleteUserByCriteria()
    {
        // create user to be deleted
        $values = $this->getUserValues();
        $user = $this->createAndFetchUser($values);
        
        $where = array('id = ?', array($user['id']));
        
        $result = $this->userTable->delete($where, array(
            'limitMax' => 1,
        ));
        
        $this->assertTrue($result);
        
        // let's try and fetch it
        
        $users = $this->userTable->fetch($where, array(
            'limitMax' => 1,
        ));
        
        $this->assertEmpty($users);
    }
    
    // protected functions
    
    protected function getUserValues(array $values=array())
    {
        // defaults
        $user = array(
            'name' => 'Martyn',
            'email' => md5(time()) . '@gmail.com',
            'password' => 'password',
            'date_created' => date("Y-m-d H:i:s"),
            'date_updated' => date("Y-m-d H:i:s"),
        );
        
        // overwrite
        foreach($values as $key => $value) {
            if(array_key_exists($key, $user)) $user[$key] = $value;
        }
        
        return $user;
    }
    
    public function createAndFetchUser($values)
    {
        // create
        $result = $this->userTable->create($values);
        
        // fetch
        $user = $this->userTable->fetch(array('email = ?', array($values['email'])), array(
            'limitMax' => 1,
        ))[0];
        
        return $user;
    }
}
