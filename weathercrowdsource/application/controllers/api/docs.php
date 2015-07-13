<?php

// @formatter:off
/**
 * 
 * @SWG\Info(
 *   title="iRain API",
 *   version="1.0.0",
 *   description="An API to access iRain data",
 *   @SWG\Contact(
 *     email="ubriela@gmail.com",
 *     name="iRain Team",
 *     url="http://irain.eng.uci.edu"
 *   ),
 *   termsOfService=""
 * )
 * @SWG\Swagger(
 *   basePath="/api",
 *   schemes={"http"},
 *   produces={"application/json", "application/xml", "text/csv", "text/html"},
 *   consumes={"application/json", "application/x-www-form-urlencoded"},
 *   
 *   @SWG\Definition(
 *         definition="errorModel",
 *         required={"code", "message"},
 *         @SWG\Property(
 *             property="code",
 *             type="integer",
 *             format="int32"
 *         ),
 *         @SWG\Property(
 *             property="message",
 *             type="string"
 *         )
 *     )
 * )
 */
// @formatter:on
