<?php

class Config
{
    /**
     * 调试模式
     */
    const DEBUG = true;

    /**
     * 每像素所需按压屏幕的时间 (毫秒)，酌情修改
     */
    const MS_PER_PIXEL = 1.365;

    /**
     * 棋子宽度，酌情修改
     */
    const WIDTH_OF_CHESSMAN = 75;

    /**
     * ADB工具路径
     */
    const ADB = 'E:\ADB\adb.exe';

    /**
     * 临时文件夹
     */
    const TEMP_PATH = 'E:\Temp';

    /**
     * 截屏文件名
     */
    const IMAGE_NAME = 'screen.png';
}
