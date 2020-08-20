<?php
namespace bigDream\CdnUrlAuth;

/**
 * 阿里云CDN鉴权链接地址生成类
 * @package bigDream\CdnUrlAuth
 * @author jwj <jwjbjg@gmail.com>
 * @copyright jwj
 */
class Aliyun
{
    /**
     * @var string 鉴权密钥
     */
    protected $secret;

    /**
     * @var string 鉴权参数名
     */
    protected $signName;

    /**
     * Tencent constructor.
     * @param $secret
     * @param string $signName
     */
    public function __construct($secret, $signName = 'auth_key')
    {
        $this->secret = $secret;
        $this->signName = $signName;
    }

    /**
     * 鉴权方式A
     * @param string $uri 需要鉴权的链接
     * @param int $timestamp 当前时间时间戳
     * @param string $rand 随机数
     * @param string $uid 用户ID
     * @return string
     * @link https://help.aliyun.com/document_detail/85113.html?source=5176.11533457&userCode=acpn6od4
     * @throws \Exception
     */
    public function typeA($uri, $timestamp = null, $rand = null, $uid = '0')
    {
        $data = $this->parseUrl($uri);

        if (null === $timestamp) $timestamp = time();
        if (null === $rand) $rand = md5(microtime());

        $md5hash = md5(sprintf('%s-%s-%s-%s-%s', $data['path'], $timestamp, $rand, $uid, $this->secret));

        $data['query'][$this->signName] = sprintf('%s-%s-%s-%s', $timestamp, $rand, $uid, $md5hash);

        return $this->buildUrl($data);
    }

    /**
     * 鉴权方式B
     * @param string $uri 需要鉴权的链接
     * @param int $timestamp 当前时间时间戳
     * @return string
     * @link https://help.aliyun.com/document_detail/85114.html?source=5176.11533457&userCode=acpn6od4
     * @throws \Exception
     */
    public function typeB($uri, $timestamp = null)
    {
        $data = $this->parseUrl($uri);

        if (null === $timestamp) $timestamp = date('YmdHi');

        $md5hash = md5($this->secret . $timestamp . $data['path']);

        $data['path'] = sprintf('/%d/%s%s', $timestamp, $md5hash, $data['path']);

        return $this->buildUrl($data);
    }

    /**
     * 鉴权方式C
     * @param string $uri 需要鉴权的链接
     * @param int $timestamp 当前时间时间戳
     * @return string
     * @link https://help.aliyun.com/document_detail/85115.html?source=5176.11533457&userCode=acpn6od4
     * @throws \Exception
     */
    public function typeC($uri, $timestamp = null)
    {
        $data = $this->parseUrl($uri);

        if (null === $timestamp) $timestamp = time();
        $timestamp = dechex($timestamp);

        $md5hash = md5($this->secret . $data['path'] . $timestamp);

        $data['path'] = sprintf('/%s/%s%s', $md5hash, $timestamp, $data['path']);

        return $this->buildUrl($data);
    }

    /**
     * 解析URL地址
     * @param string $url
     * @return array
     * @throws \Exception
     */
    public function parseUrl($url)
    {
        $data = parse_url($url);
        if (false === $data) {
            throw new \Exception('parse url error');
        }

        if (array_key_exists('query', $data)) {
            parse_str($data['query'], $data['query']);
        } else {
            $data['query'] = [];
        }

        return $data;
    }

    /**
     * 生成网址
     * @param array $data
     * @return string
     */
    public function buildUrl($data)
    {
        $url = '';

        if (array_key_exists('host', $data)) {
            // 协议
            $url .= array_key_exists('scheme', $data) ? $data['scheme'] . '://' : '//';
            // 主机名
            $url .= $data['host'];
            // 端口
            if (array_key_exists('port', $data)) $url .= ':' . $data['port'];
        }

        // 路径
        $url .= $data['path'];
        // 查询参数
        if (!empty($data['query'])) $url .= '?' . http_build_query($data['query']);
        // 锚点
        if (array_key_exists('fragment', $data)) $url .= '#' . $data['fragment'];

        return $url;
    }
}