<?php 

namespace UserTest\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

/**
* UserControllerTest
*    
* Test for User controller
*    
*/
class UserControllerTest extends AbstractHttpControllerTestCase
{
    protected $userTable;
    
    /**
    * setUp
    * 
    * Initialise the test file
    * 
    */
    public function setUp()
    {
        $this->setApplicationConfig(
            include '/var/www/userModule/config/application.config.php'
        );
        parent::setUp();
    }
    
    
    
    
    /**
    * testRegisterActionCanBeAccessed
    *    
    * desc
    *    
    */
    public function testRegisterActionCanBeAccessed()
    {
        $this->dispatch('/user');
        
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('User');
        $this->assertControllerName('User\Controller\User');
        $this->assertControllerClass('UserController');
        $this->assertMatchedRouteName('user');
    }
    
    /**
    * testRegisterActionRedirectsAfterValidPost
    *    
    * desc
    *    
    */
    public function testRegisterActionRedirectsAfterValidPost()
    {
        // create mock user table
        
        $userTableMock = $this->getMockBuilder('User\Model\UserTable')
            ->disableOriginalConstructor()
            ->getMock();
        
        $userTableMock->expects( $this->once() )
            ->method('saveUser')
            ->will( $this->returnValue(null) );
        
        // overwrite service manager settings
        
        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('User\Model\UserTable', $userTableMock);
        
        $postData = $this->getRegisterUserValues();
        
        $this->dispatch('/user/register', 'POST', $postData);
        $this->assertResponseStatusCode(302);
        
        $this->assertRedirectTo('/');
    }
    
    /**
    * testRegisterActionDoesNotRedirectsAfterInvalidPost
    *    
    * desc
    * 
    * @dataProvider invalidRegisterDataProvider
    *    
    */
    public function testRegisterActionDoesNotRedirectsAfterInvalidPost(array $invalidData)
    {
        // create mock user table (by creating a mock we ensure that nothing is written to the db, but we shouldn't need it though)
        
        $userTableMock = $this->getMockBuilder('User\Model\UserTable')
            ->disableOriginalConstructor()
            ->getMock();
        
        // overwrite service manager settings
        
        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('User\Model\UserTable', $userTableMock);
        
        // perform test
        
        $postData = $this->getRegisterUserValues($invalidData);
        
        $this->dispatch('/user/register', 'POST', $postData);
        $this->assertResponseStatusCode(200);
    }
    
    
    
    
    
    
    /**
    * testLoginActionCanBeAccessed
    *    
    * desc
    *    
    */
    public function testLoginActionCanBeAccessed()
    {
        $this->dispatch('/user/login');
        
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('User');
        $this->assertControllerName('User\Controller\User');
        $this->assertControllerClass('UserController');
        $this->assertMatchedRouteName('user');
    }
    
#    /**
#    * testLoginRedirectsWithValidCredentials
#    *    
#    * desc
#    *    
#    */
#    public function testLoginRedirectsWithValidCredentials()
#    {
#        $postData = $this->getLoginUserValues();
#        
#        $this->dispatch('/user/login', 'POST', $postData);
#        $this->assertResponseStatusCode(302);
#    }
#    
#    /**
#    * testLoginDoesNotRedirectWithInvalidCredentials
#    *    
#    * desc
#    *    
#    */
#    public function testLoginDoesNotRedirectWithInvalidCredentials()
#    {
#        $postData = $this->getLoginUserValues();
#        
#        $this->dispatch('/user/login', 'POST', $postData);
#        $this->assertResponseStatusCode(200);
#        
#        $this->fail('Not yet implemented'); // remove
#    }
    
    
    
    
    
    /**
    * testLogoutActionCanBeAccessed
    *    
    * desc
    *    
    */
    public function testLogoutActionCanBeAccessed()
    {
        // create mock user table (by creating a mock we ensure that nothing is written to the db, but we shouldn't need it though)
        
        $authServiceMock = $this->getMockBuilder('Zend\Authentication\AuthenticationService')
            ->disableOriginalConstructor()
            ->getMock();
        
        $authServiceMock->expects( $this->once() )
            ->method('clearIdentity')
            ->will( $this->returnValue(null) );
        
        // overwrite service manager settings
        
        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('User\Controller\AuthService', $authServiceMock);
        
        $this->dispatch('/user/logout');
        
#        $this->assertResponseStatusCode(302);
#        $this->assertModuleName('User');
#        $this->assertControllerName('User\Controller\User');
#        $this->assertControllerClass('UserController');
#        $this->assertMatchedRouteName('user');
    }
    
    
    
    
    
    /**
    * getRegisterUserValues
    * 
    * Return a default array of user values with arguments merged in
    * 
    */
    protected function getRegisterUserValues(array $values=array())
    {
        return array_merge(array(
            'firstName' => 'Joe', 
            'lastName' => 'Bloggs', 
            'email' => 'user@example.com', 
            'password' => 'foo',
            'passwordConfirm' => 'foo',
        ), $values);
    }
    
    /**
    * getRegisterUserValues
    * 
    * Return a default array of user values with arguments merged in
    * 
    */
    protected function getLoginUserValues(array $values=array())
    {
        return array_merge(array(
            'email' => 'user@example.com', 
            'password' => 'foo',
        ), $values);
    }
    
    /**
    * invalidRegisterDataProvider
    *    
    * Many variations of invalid data. Only the field to test needs to be provided, the rest will be set in getRegisterUserValues.
    *    
    */
    public function invalidRegisterDataProvider()
    {
        return array(
            // empty strings
            array(
                array(
                    'firstName' => '',
                )
            ),
            array(
                array(
                    'lastName' => '',
                )
            ),
            array(
                array(
                    'email' => '',
                )
            ),
            array(
                array(
                    'password' => '',
                )
            ),
            array(
                array(
                    'passwordConfirm' => '',
                )
            ),
            
            // email format
            array(
                array(
                    'email' => 'user',
                )
            ),
            array(
                array(
                    'email' => 'user@',
                )
            ),
            array(
                array(
                    'email' => 'user@example',
                )
            ),
            
            // out of range
            array(
                array(
                    'firstName' => 'abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi a', //101
                )
            ),
            array(
                array(
                    'lastName' => 'abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi abcdefghi a', //101
                )
            ),
            
            // passwords match
            array(
                array(
                    'password' => 'a',
                    'passwordConfirm' => 'b',
                )
            ),
        );
    }
}
