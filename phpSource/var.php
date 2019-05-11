<?
	$db_host = "localhost";
	$db_user = "root";
	$db_password = "livish++";
	$db_dbname = "v_farm";

	define("REG_DEV",				0x01);
	define("GET_DEV_CONF",			0x02);
	define("GET_PM_STATION",		0x03);
	define("SET_DEV_INFO",			0x04);
	define("SET_LOG",				0x05);
	define("SET_FWUP_FLAG",			0x06);
	define("SET_DEV_NOTI",			0x07);
	define("GET_CNT_DEV",			0x08);
	define("GET_DEV_INFO",			0x09);
	define("GET_DEV_INFO_EX",		0x0A);
	define("GET_PROFILE",			0x0B);
	define("GET_FWUP_FLAG",			0x0C);
	define("GET_GRAPH_DATA",		0x0D);
	define("GET_EXTRA_ADMIN",		0x0E);
	define("SET_PROFILE_MODE",		0x0F);
	define("SET_CTRL_RELAY",		0x10);
	define("SET_WORK_TYPE",			0x11);
	define("SET_FWUP_MODE",			0x12);
	define("SET_RM_DEV",			0x13);
	define("SET_EXTRA_ADMIN",		0x14);
	define("SET_DEV_NETWORK",		0x15);
	define("SET_REG_FCMUSER",		0x16);
	define("GET_EMERGENCY_INFO",	0x17);
	define("SET_EMERGENCY_INFO",	0x18);
	define("GET_SOIL_INFO",			0x19);
	define("SET_CAM_UPLOAD",		0x1A);
	define("SET_CAM_UPLOAD_LOOP",	0x1B);
	define("GET_CAM_IP",			0x1C);
	define("GET_IMG_INFO",			0x1D);
	define("SET_CAM_UPLOAD_EX",		0x1E);
	define("SET_CAM_INFO",			0x1F);
	

	define("NOTI_WARN_WATERLV",		0x00);
	define("NOTI_HAVEST",			0x01);
	define("NOTI_WARN_CERR",		0x02);
	define("NOTI_EVENT",			0x03);
	define("NOTI_CHG_WORK_CONF",	0x04);
	define("NOTI_CHG_NETWORK_CONF",	0x05);
	define("NOTI_EMERGENCY",		0x06);
	define("NOTI_REBOOT",			0x07);

	define("NOTI_FULL_WATERLV",		0x08);
	define("NOTI_NOTFULL_WATERLV",	0x09);

	define("KOR_PM2_5_LV0",	0);
	define("KOR_PM2_5_LV1",	16);
	define("KOR_PM2_5_LV2",	31);
	define("KOR_PM2_5_LV3",	76);

	define("KOR_PM10_LV0",	0);
	define("KOR_PM10_LV1",	31);
	define("KOR_PM10_LV2",	81);
	define("KOR_PM10_LV3",	151);

?>
