<?php

use Symfony\Component\HttpFoundation\ParameterBag;
use Illuminate\Http\Request;
use App\Models\RequestImageReader;
use Illuminate\Http\UploadedFile;

class RequestImageReaderTest extends TestCase
{
    /**
     * Make protected/private class method public
     * @param Class name $className
     * @param Method name $methodName
     * @return ReflectionMethod A ReflectionMethod
     */
    protected static function getMethod($className, $methodName) {
        $class = new ReflectionClass($className);
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);
        return $method;
    }

    /**
     * Check getImageItemsFromJson method
     *
     * @return void
     */
    public function testGetImageItemsFromJson()
    {
        $json = $this->getMockBuilder(ParameterBag::class)
            ->disableOriginalConstructor()
            ->setMethods(['all'])
            ->getMock();

        $json->expects($this->any())
            ->method('all')
            ->willReturn(['json-item-1','','json-item-2']);

        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['isJson', 'json'])
            ->getMock();

        $request->expects($this->any())
            ->method('isJson')
            ->willReturn(true);

        $request->expects($this->any())
            ->method('json')
            ->willReturn($json);


        $requestImageReader = new RequestImageReader($request);

        $getImageItemsFromJson = self::getMethod(RequestImageReader::class, 'getImageItemsFromJson');

        $result = $getImageItemsFromJson->invokeArgs($requestImageReader, []);

        $this->assertEquals($result, [
            'json-item-1',
            'json-item-2',
        ]);
    }

    /**
     * Check getImageItemsFromMultipartForm method
     *
     * @return void
     */
    public function testGetImageItemsFromMultipartForm()
    {
        $file_1 = UploadedFile::fake()->image('fake-test-image-1.jpg');
        $file_2 = UploadedFile::fake()->image('fake-test-image-2.jpg');

        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['isJson', 'file'])
            ->getMock();

        $request->expects($this->any())
            ->method('isJson')
            ->willReturn(false);

        $request->expects($this->any())
            ->method('file')
            ->with('imageFile')
            ->willReturn([$file_1, $file_2]);

        $requestImageReader = new RequestImageReader($request);

        $getImageItemsFromMultipartForm = self::getMethod(RequestImageReader::class, 'getImageItemsFromMultipartForm');

        $result = $getImageItemsFromMultipartForm->invokeArgs($requestImageReader, []);

        $this->assertEquals($result, [
            $file_1,
            $file_2,
        ]);
    }

    /**
     * Check getImageItemsFromForm method
     *
     * @return void
     */
    public function testGetImageItemsFromForm()
    {
        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['isJson', 'post'])
            ->getMock();

        $request->expects($this->any())
            ->method('isJson')
            ->willReturn(false);

        $request->expects($this->any())
            ->method('post')
            ->with('imageFile')
            ->willReturn(['item-1', '', 'item-2']);

        $requestImageReader = new RequestImageReader($request);

        $getImageItemsFromForm = self::getMethod(RequestImageReader::class, 'getImageItemsFromForm');

        $result = $getImageItemsFromForm->invokeArgs($requestImageReader, []);

        $this->assertEquals($result, [
            'item-1',
            'item-2',
        ]);
    }

    /**
     * Check getImageItems method for JSON request
     *
     * @return void
     */
    public function testGetImageItemsForJsonRequest()
    {
        $file_1 = UploadedFile::fake()->image('fake-test-image-1.jpg');
        $file_2 = UploadedFile::fake()->image('fake-test-image-2.jpg');

        $json = $this->getMockBuilder(ParameterBag::class)
            ->disableOriginalConstructor()
            ->setMethods(['all'])
            ->getMock();

        $json->expects($this->any())
            ->method('all')
            ->willReturn(['json-item-1','','json-item-2']);

        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['isJson', 'json', 'post', 'file'])
            ->getMock();

        $request->expects($this->any())
            ->method('isJson')
            ->willReturn(true);

        $request->expects($this->any())
            ->method('json')
            ->willReturn($json);

        $request->expects($this->any())
            ->method('file')
            ->with('imageFile')
            ->willReturn([$file_1, $file_2]);

        $request->expects($this->any())
            ->method('post')
            ->with('imageFile')
            ->willReturn(['item-1', '', 'item-2']);

        $requestImageReader = new RequestImageReader($request);

        $result = $requestImageReader->getImageItems();

        $this->assertEquals($result, [
            'json-item-1',
            'json-item-2',
        ]);
    }

    /**
     * Check getImageItems method for NOT JSON request
     *
     * @return void
     */
    public function testGetImageItemsForNotJsonRequest()
    {
        $file_1 = UploadedFile::fake()->image('fake-test-image-1.jpg');
        $file_2 = UploadedFile::fake()->image('fake-test-image-2.jpg');

        $json = $this->getMockBuilder(ParameterBag::class)
            ->disableOriginalConstructor()
            ->setMethods(['all'])
            ->getMock();

        $json->expects($this->any())
            ->method('all')
            ->willReturn(['json-item-1','','json-item-2']);

        $request = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->setMethods(['isJson', 'json', 'post', 'file'])
            ->getMock();

        $request->expects($this->any())
            ->method('isJson')
            ->willReturn(false);

        $request->expects($this->any())
            ->method('json')
            ->willReturn($json);

        $request->expects($this->any())
            ->method('file')
            ->with('imageFile')
            ->willReturn([$file_1, $file_2]);

        $request->expects($this->any())
            ->method('post')
            ->with('imageFile')
            ->willReturn(['item-1', '', 'item-2']);

        $requestImageReader = new RequestImageReader($request);

        $result = $requestImageReader->getImageItems();

        $this->assertEquals($result, [
            $file_1->getRealPath(),
            $file_2->getRealPath(),
            'item-1',
            'item-2',
        ]);
    }
}