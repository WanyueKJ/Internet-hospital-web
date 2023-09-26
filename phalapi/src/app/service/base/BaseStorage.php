<?php


namespace App\service\base;



use App\ApiException;

/**
 * Class BaseStorage
 */
abstract class BaseStorage
{

    /**
     * 驱动名称
     * @var string
     */
    protected $name;

    /**
     * 驱动配置文件名
     * @var string
     */
    protected $configFile;

    /**
     * BaseStorage constructor.
     * @param string $name 驱动名
     * @param array $config 其他配置
     * @param string $configFile 驱动配置名
     */
    public function __construct(string $name, array $config = [], string $configFile = null)
    {
        $this->name = $name;
        $this->configFile = $configFile;
        $this->initialize($config);
    }

    /**
     * 初始化
     * @param array $config
     * @return mixed
     */
    abstract protected function initialize(array $config);


    /**
     * 设置错误信息
     * @param string|null $error
     * @return bool
     */
    protected function setError(?string $error = null)
    {
        throw new ApiException($error ?: '未知错误');
    }
}
