 <?php  
		$map=array("default"=>"State");
		
		$map["AL"]="Alabama";	
		$map["AK"]="Alaska";	
		$map["AZ"]="Arizona";	
		$map["AR"]="Arkansas";	
		$map["CA"]="California";	
		$map["CO"]="Colorado";	
		$map["CT"]="Connecticut";	
		$map["DE"]="Delaware";	
		$map["DC"]="District Of Columbia";	
		$map["FL"]="Florida";	
		$map["GA"]="Georgia";	
		$map["HI"]="Hawaii";	
		$map["ID"]="Idaho";	
		$map["IL"]="Illinois";	
		$map["IN"]="Indiana";	
		$map["IA"]="Iowa";	
		$map["KS"]="Kansas";	
		$map["KY"]="Kentucky";	
		$map["LA"]="Louisiana";	
		$map["ME"]="Maine";	
		$map["MD"]="Maryland";	
		$map["MA"]="Massachusetts";	
		$map["MI"]="Michigan";	
		$map["MN"]="Minnesota";	
		$map["MS"]="Mississippi";	
		$map["MO"]="Missouri";	
		$map["MT"]="Montana";	
		$map["NE"]="Nebraska";	
		$map["NV"]="Nevada";	
		$map["NH"]="New Hampshire";	
		$map["NJ"]="New Jersey";	
		$map["NM"]="New Mexico";	
		$map["NY"]="New York";	
		$map["NC"]="North Carolina";	
		$map["ND"]="North Dakota";	
		$map["OH"]="Ohio";	
		$map["OK"]="Oklahoma";	
		$map["OR"]="Oregon";	
		$map["PA"]="Pennsylvania";	
		$map["RI"]="Rhode Island";	
		$map["SC"]="South Carolina";	
		$map["SD"]="South Dakota";	
		$map["TN"]="Tennessee";	
		$map["TX"]="Texas";	
		$map["UT"]="Utah";	
		$map["VT"]="Vermont";	
		$map["VA"]="Virginia";	
		$map["WA"]="Washington";	
		$map["WV"]="West Virginia";	
		$map["WI"]="Wisconsin";	
		$map["WY"]="Wyoming";	
?>

<?php
	$tester=true;
	if($_SERVER['REQUEST_METHOD']=='POST')
	{
		if($_POST['hiddenCity']!="")
		{
			$latitude= $_POST['hiddenLat'];
			$longitude= $_POST['hiddenLon'];
			$city=$_POST['hiddenCity'];
		}
		else
		{
			$geocode_api_key="AIzaSyBERRcQ5RSrO3l4KgEKOW__y-Aw-h5t7K0";
			$street=urlencode($_POST['street']);
			$city=urlencode($_POST['city']);
			$state=urlencode($_POST['state']);
			$basic_url_geocode="https://maps.googleapis.com/maps/api/geocode/xml?address=";
			$geocode_url=$basic_url_geocode.$street.",+".$city.",+".$state."&key=".$geocode_api_key; 

			$geocode_xml_data = simplexml_load_file($geocode_url) or die("Failed to load");

			if($geocode_xml_data->status=="ZERO_RESULTS")
			{
				$tester=false;

			}
			else
			{
				$latitude= $geocode_xml_data->result->geometry->location->lat;
				$longitude= $geocode_xml_data->result->geometry->location->lng;
				$city=$_POST['city'];
			}
		}
		
	}			
?>

<?php
	if($_SERVER['REQUEST_METHOD']=='POST' && $tester)
	{
		$darksky_api_key="49d3a935108786a4ec4101d0ede43d30";
		$basic_url_darksky="https://api.forecast.io/forecast/";
		$basic_url_darksky_end="?exclude=minutely,hourly,alerts,flags";
		$darksky_url=$basic_url_darksky.$darksky_api_key."/".$latitude.",".$longitude.$basic_url_darksky_end;

		$darksky_json = file_get_contents($darksky_url);
		$darksky_obj = json_decode($darksky_json,true);
			
		$timezone=$darksky_obj["timezone"];	
		$currently=$darksky_obj["currently"];

		$temperature=$currently["temperature"];
		$summary=$currently["summary"];
		$humidity=$currently["humidity"];
		$pressure=$currently["pressure"];
		$windSpeed=$currently["windSpeed"];
		$visibility=$currently["visibility"];
		$cloudCover=$currently["cloudCover"];
		$ozone=$currently["ozone"];

		$daily=$darksky_obj["daily"];
		$data_daily=$daily["data"];
	}

?>

<?php

	if($_SERVER['REQUEST_METHOD']=='POST' && $tester)
	{

		$card="<div class='card'><div class='card_content'>";

		$card.="<div class='card_row1'><h2>".$city."</h2></div>";

		$card.="<div class='card_row2'>".$timezone."</div>";

		$card.="<div class='card_row3'><h1>".intval($temperature)."</h1>
				<img class='img_temp' src='https://cdn3.iconfinder.com/data/icons/virtual-notebook/16/button_shape_oval-512.png'>
				<div class='far'>F</div>
				</div>";

		$card.="<div class='card_row4'><h2>".$summary."</h2></div>";


		$card.="<div class='card_row5'><table class='table_1'><tr>";

		if(array_key_exists("humidity",$currently))
		{
			$card.="<th><img title='Humidity' id='_h' class='img_table' src='https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-16-512.png'></th>";
		}
		if(array_key_exists("pressure",$currently))
		{
			$card.="<th><img title='Pressure' class='img_table' id='_p' src='https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-25-512.png'></th>";
		}
		if(array_key_exists("windSpeed",$currently))
		{
			$card.="<th><img title='WindSpeed' class='img_table' id='_w' src='https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-27-512.png'></th>";
		}
		if(array_key_exists("visibility",$currently))
		{
			$card.="<th><img title='Visibility' class='img_table' id='_v' src='https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-30-512.png'></th>";
		}
		if(array_key_exists("cloudCover",$currently))
		{
			$card.="<th><img title='Cloud Cover'' class='img_table' id='_c' src='https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-28-512.png'></th>";
		}
		if(array_key_exists("ozone",$currently))
		{
			$card.="<th><img title='Ozone' class='img_table' id='_o' src='https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-24-512.png'></th>";
		}
				
		$card.=	"</tr><tr class='table_row_2'>";


		if(array_key_exists("humidity",$currently))
		{
			$card.="<th>".$humidity."&nbsp;</th>";
		}
		if(array_key_exists("pressure",$currently))
		{
			$card.="<th>".$pressure."&nbsp;</th>";
		}
		if(array_key_exists("windSpeed",$currently))
		{
			$card.="<th>".$windSpeed."&nbsp;</th>";
		}
		if(array_key_exists("visibility",$currently))
		{
			$card.="<th>".$visibility."&nbsp;</th>";
		}
		if(array_key_exists("cloudCover",$currently))
		{
			$card.="<th>".$cloudCover."&nbsp;</th>";
		}
		if(array_key_exists("ozone",$currently))
		{
			$card.="<th>".$ozone."</th>";
		}

		$card.="</tr></table></div></div></div>";
	}
?>

<?php
	if($_SERVER['REQUEST_METHOD']=='POST' && $tester)
	{
		
		$table="<div class='week_table'><table class='weekTable'>
				<tr>
					<th>Date</th>
					<th>Status</th>
					<th>Summary</th>
					<th>TemperatureHigh</th>
					<th>TemperatureLow</th>
					<th>Wind Speed</th>
				</tr>";

		$cards=array("Zero Index");
		$googlechart=array("Zero Index");
		$trial=0;
		foreach($data_daily as $data)
		{
			$linkedPageURL="https://api.darksky.net/forecast/".$darksky_api_key."/".$latitude.",".$longitude.",".$data['time']."?exclude=minutely";

			$ch2 = curl_init();
			curl_setopt($ch2, CURLOPT_URL,$linkedPageURL);
			curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
			$linkedPage_o = curl_exec($ch2);
			curl_close($ch2);
			$linkedPage_obj=json_decode($linkedPage_o,true);

			$temps=[];
			$xyzz=$linkedPage_obj["hourly"];
			$abcc=$xyzz["data"];
			foreach($abcc as $a)
			{
				array_push($temps, $a["temperature"]);
			}
			array_push($googlechart, $temps);

			$linkedPage_obj_currently=$linkedPage_obj['currently'];
			$lpoc_summary=$linkedPage_obj_currently['summary'];
			$lpoc_temperature=$linkedPage_obj_currently['temperature'];
			$lpoc_icon=$linkedPage_obj_currently['icon'];
			$lpoc_precipitation=$linkedPage_obj_currently['precipIntensity'];
			$lpoc_chanceRain=$linkedPage_obj_currently['precipProbability'];
			$lpoc_windSpeed=$linkedPage_obj_currently['windSpeed'];
			$lpoc_humidity=$linkedPage_obj_currently['humidity'];
			$lpoc_visibility=$linkedPage_obj_currently['visibility'];
			$lpoc_sunrise=$data['sunriseTime'];
			$lpoc_sunset=$data['sunsetTime'];

			$sunriseTime_converted=new DateTime('@'.$lpoc_sunrise);
			$sunriseTime_converted->setTimeZone(new DateTimeZone($timezone));

			$sunsetTime_converted=new DateTime('@'.$lpoc_sunset);
			$sunsetTime_converted->setTimeZone(new DateTimeZone($timezone));

			if($lpoc_icon=="clear-day" || $lpoc_icon=="clear-night")
			{
				$lpoc_icon_img="https://cdn3.iconfinder.com/data/icons/weather-344/142/sun-512.png";
			}
			else if($lpoc_icon=="rain")
			{
				$lpoc_icon_img="https://cdn3.iconfinder.com/data/icons/weather-344/142/rain-512.png";
			}
			else if($lpoc_icon=="snow")
			{
				$lpoc_icon_img="https://cdn3.iconfinder.com/data/icons/weather-344/142/snow-512.png";
			}
			else if($lpoc_icon=="sleet")
			{
				$lpoc_icon_img="https://cdn3.iconfinder.com/data/icons/weather-344/142/lightning-512.png";
			}
			else if($lpoc_icon=="wind")
			{
				$lpoc_icon_img="https://cdn4.iconfinder.com/data/icons/the-weather-is-nice-today/64/weather_10-512.png";
			}
			else if($lpoc_icon=="fog")
			{
				$lpoc_icon_img="https://cdn3.iconfinder.com/data/icons/weather-344/142/cloudy-512.png";
			}
			else if($lpoc_icon=="cloudy")
			{
				$lpoc_icon_img="https://cdn3.iconfinder.com/data/icons/weather-344/142/cloud-512.png";
			}
			else
			{
				$lpoc_icon_img="https://cdn3.iconfinder.com/data/icons/weather-344/142/sunny-512.png";
			}

			if($lpoc_precipitation <=0.001)
			{
				$lpoc_precipitation_str="None";
			}
			else if($lpoc_precipitation <=0.015)
			{
				$lpoc_precipitation_str="Very Light";
			}
			else if($lpoc_precipitation <=0.05)
			{
				$lpoc_precipitation_str="Light";
			}
			else if($lpoc_precipitation <=0.1)
			{
				$lpoc_precipitation_str="Moderate";
			}
			else 
			{
				$lpoc_precipitation_str="Heavy";
			}


			$trial+=1;
			$linkedPageHTML="";
			$linkedPageHTML.="<div class='linkedPageContainer'><div class='linkedPagePart1Header'><h2>Daily Weather Detail</h2></div><div class='linkedPagePart1Body'>";
			$linkedPageHTML.="<div class='lPP1B1'><h2 class='h2_lPP1B1'>".$lpoc_summary."</h2></div>";
			$linkedPageHTML.="<div class='lPP1B2'><h1>".intval($lpoc_temperature)."</h1><img class='lPP1B2_img_temp' src='https://cdn3.iconfinder.com/data/icons/virtual-notebook/16/button_shape_oval-512.png'><div class='lPP1B2_far'>F</div></div>";
			$linkedPageHTML.="<div class='lPP1B3'><img class='lPP1B3img' src='".$lpoc_icon_img."'></div>";
			$linkedPageHTML.="<b><div class='lPP1B4'>Precipitation: <span class='lPP1Bdata'>".$lpoc_precipitation_str."</span></div>";
			$linkedPageHTML.="<div class='lPP1B5'>Chance of Rain: <span class='lPP1Bdata'>".intval($lpoc_chanceRain*100)."<span class='lPP1Bsymbol'>&nbsp;%</span>"."</span></div>";
			$linkedPageHTML.="<div class='lPP1B6'>Wind Speed: <span class='lPP1Bdata'>".$lpoc_windSpeed ."<span class='lPP1Bsymbol'> mph</span>"."</span></div>";
			$linkedPageHTML.="<div class='lPP1B7'>Humidity: <span class='lPP1Bdata'>".intval($lpoc_humidity*100)."<span class='lPP1Bsymbol'>&nbsp;%</span>"."</span></div>";
			$linkedPageHTML.="<div class='lPP1B8'>Visibility: <span class='lPP1Bdata'>".$lpoc_visibility."<span class='lPP1Bsymbol'> mi</span>"."</span></div>";
			$linkedPageHTML.="<div class='lPP1B9'>Sunrise/Sunset: <span class='lPP1Bdata'>".$sunriseTime_converted->format('g')."<span class='lPP1Bsymbol'>&nbsp;".$sunriseTime_converted->format('A')."</span>/".$sunsetTime_converted->format('g')."<span class='lPP1Bsymbol'>&nbsp;".$sunsetTime_converted->format('A')."</span></span></div></b>";

			$linkedPageHTML.="</div>";

			$linkedPageHTML.="<div class='chart'><div class='chartHeader'><h2>Day's Hourly Weather</h2></div>";
			$linkedPageHTML.="<div class='chartbutton'><img class='linker chartimg' src='https://cdn4.iconfinder.com/data/icons/geosm-e-commerce/18/point-down-512.png' onclick='loadimage(this)'></div>";
			$linkedPageHTML.="<div id='curve_chart'></div";


			$linkedPageHTML.="</div>";
			$linkedPageHTML.="</div>";

			array_push($cards, $linkedPageHTML);

			$date=date('Y-m-d', $data["time"]);
			$icon=$data["icon"];
			if($icon=="clear-day" || $icon=="clear-night")
			{
				$icon_img="https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-12-512.png";
			}
			else if($icon=="rain")
			{
				$icon_img="https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-04-512.png";
			}
			else if($icon=="snow")
			{
				$icon_img="https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-19-512.png";
			}
			else if($icon=="sleet")
			{
				$icon_img="https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-07-512.png";
			}
			else if($icon=="wind")
			{
				$icon_img="https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-27-512.png";
			}
			else if($icon=="fog")
			{
				$icon_img="https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-28-512.png";
			}
			else
			{
				$icon_img="https://cdn2.iconfinder.com/data/icons/weather-74/24/weather-02-512.png";
			}

			
			$summary=$data["summary"];
			$temperatureHigh=$data["temperatureHigh"];
			$temperatureLow=$data["temperatureLow"];
			$windSpeed=$data["windSpeed"];
			$table.="<tr>".
					"<th>".$date."</th>".
					"<th><img src='".$icon_img."' class='table_img'></th>". 
					"<th><span class='linker' id='".$trial."' onclick='newText(this)'>".$summary."</span></th>".
					"<th>".$temperatureHigh."</th>".
					"<th>".$temperatureLow."</th>".
					"<th>".$windSpeed."</th>".
					"</tr>";
		}
		$table.="</table>";
	}
?>



<!DOCTYPE html>
<html>
	<head>
		<title>Weather Search</title>
		<style>
			*{
   				margin:0;
   				padding:0;
			}
			.wrapper
			{
				padding-top: 30px;
				width: 700px;
			  	left: 0;
			  	right:0;
			  	margin: auto;
			  	
			}
			.fixedTop
			{
				border-radius: 10px;
				color: white;
				height: 220px;
				background-color: #01A61C;
			}
			.header
			{
				padding-top: 5px;
				font-size: 35px;
				text-align: center;
			}
			.columns 
			{
				float: left;
  				width: 50%;
			}
			.below
			{
				position: absolute;
				padding-top: 55px;
				padding-left: 230px;
			}
			.search_Form
			{	
				padding-left: 30px;
			}
			.verticalLine
			{
				margin-top: 5px;
				border-radius: 5px;
				border-left: 4px solid white;
				height: 120px;
				position: absolute;
				left: 52.5%;
			}
			.cL
			{
				padding-left: 160px;
			}
			input[type=text]
			{
  				margin:4px;
  				width: 140px;
			}

			select
			{
				height: 17px;
  				margin-top:4px;
			}
			#errorMsg
			{
				text-align: center;
				left: 0;
			  	right:0;
			  	margin: auto;
				margin-top: 30px;
				width: 400px;
				height: 20px;
				border: 2px solid #979797;
				background-color: #F0EEF1;
				visibility: hidden;
			}

			#search
			{
				background-color: white;
				border-radius: 3px;
				width: 50px;
				border: 0 none;

			}
			#clear
			{
				background-color: white;
				border-radius: 3px;
				width: 50px;
				border: 0 none;
			}
			.dynamicCard
			{
				margin-top: -20px;
			}
			.card
			{
				margin: auto;
				color: white;
				background-color: #23BFFA;
				height: 340px;
				width: 440px;
				border-radius: 20px;
			}
			.card_content
			{
				padding-top: 10px;
				padding-left: 20px;
			}
			.card_row1
			{
				margin-top: 15px;
				font-size: 25px;
			}
			.card_row2
			{
				margin-top: 5px;
			}
			.card_row3
			{
				position: relative;
				margin-top: 5px;
				font-size: 50px;
			}
			.card_row4
			{
				font-size: 25px;
			}
			.card_row5
			{
				margin-top: 20px;
			}
			.img_temp
			{
				position: absolute;
				margin-top: -96px;
    			margin-left: 105px;
				width: 15px;
				height: 15px;
			}
			.far
			{
				margin-top: -67px;
    			margin-left: 130px;
			}
			.img_table
			{
				width: 30px;
				height: 30px;
			}
			.table_1{ 
				margin-left: -20px;
				position: relative;
				border-spacing: 30px 0px;
				margin-top: -5px;
			}
			.table_row_3 th
			{
				background-color: #D7EAF1;
				color: black;
			}
			#hover_h,#hover_p,#hover_w,#hover_v,#hover_c,#hover_o{
				display: none;
			}

			/*CSS For Table */
		
			.dynamicTable
			{
				margin-left: -94px;
				margin-top: 50px;
			}
			.week_table{
				margin-bottom: 50px;
			width: 888px;
			background-color: #8AC3EE;
			}
			.weekTable {
				width: 888px;
				height: 500px;
				color: white;
				margin: auto;
	 		 	border-collapse: collapse;
			}
			.weekTable th,td{
				padding: 5px;
				border: 2px solid #4F92BF;
			}
			.table_img
			{
				width: 40px;
				height: 40px;
			}

			/*CSS for Linked Page Card*/
			
			.linkedPageContainer
			{
				margin-top: -30px;
			}
			.linkedPagePart1Header
			{
				text-align: center;
				height: 50px;
				font-size: 25px;
			}
			.linkedPagePart1Body
			{
				margin-left: 70px;
				margin-top: 10px;
				color: white;
				background-color: #93CBD8;
				height: 450px;
				width: 560px;
				border-radius: 20px;
			}
			.lPP1B1
			{
				padding-left: 30px;
				padding-top:70px; 
				
			}
			.h2_lPP1B1
			{
				font-size: 29px;	
			}
			.lPP1B2
			{
				padding-left: 30px;
				font-size: 55px;
			}
			.lPP1B2_img_temp
			{
				position: absolute;
				margin-top: -110px;
				margin-left: 115px;
				width: 15px;
				height: 15px;
			}
			.lPP1B2_far
			{
				margin-top: -95px;
    			margin-left: 135px;
    			font-size: 80px;
    			font-weight: 700;

			}
			.lPP1B3img
			{
				margin-left: 310px;
   			 	margin-top: -230px;
    			width: 240px;
    			height: 240px;
			}
			.lPP1Bdata
			{
				font-size: 25px;
			}
			.lPP1Bsymbol
			{
				font-size: 18px;
			}
			.lPP1B4
			{
				margin-left: 225px;
				font-size: 18px;
			}
			.lPP1B5
			{
				margin-left: 208px;
				font-size: 18px;
			}
			.lPP1B6
			{
				margin-left: 234px;
				font-size: 18px;
			}
			.lPP1B7
			{
				margin-left: 254px;
				font-size: 18px;
			}
			.lPP1B8
			{
				margin-left: 260px;
				font-size: 18px;
			}
			.lPP1B9
			{
				margin-left: 216px;
				font-size: 18px;
			}
			.chart
			{
				margin-top: 30px;
			}
			.chartbutton
			{
				text-align: center;
			}
			.chartimg
			{
				width: 40px;
				height: 40px;
			}
			.chartHeader
			{
				text-align: center;
				height: 50px;
				font-size: 25px;
			}
			#curve_chart
			{
				margin-top: -10px;
				height: 40px;
			}

			.linker
			{
				cursor: pointer;
			}
			.cLextra
			{
				position:absolute;
				margin-top: -2px;
			}
			


		</style>


		
	</head>

	<body>

		<div class="wrapper">
			<div  class="fixedTop">
				<div class="header">
					<i>Weather Search</i>
				</div>
				<div class="columns">
					<div  class="search_Form">
						<form name="searchForm" id="searchForm" method="POST" action="">
							<input type="hidden" id="hiddenCity" name="hiddenCity" value="">
							<input type="hidden" id="hiddenLat" name="hiddenLat" value="">
							<input type="hidden" id="hiddenLon" name="hiddenLon" value="">
							<b>Street</b> <input type="text" id="street" name="street" size="20" 
							value="<?php echo isset($_POST['street']) ? $_POST['street'] : '' ?>"><br>
							<b>City </b><input type="text" id="city" name="city" size="20" style="margin-left: 15px";
							value="<?php echo isset($_POST['city']) ? $_POST['city'] : '' ?>"><br>
							<b>State</b> <select id="state" name="state" style="width: 200px;">
								<option name="default" id="default" selected value="default">State</option>

								<option value="AL">Alabama</option>
								<option value="AK">Alaska</option>
								<option value="AZ">Arizona</option>
								<option value="AR">Arkansas</option>
								<option value="CA">California</option>
								<option value="CO">Colorado</option>
								<option value="CT">Connecticut</option>
								<option value="DE">Delaware</option>
								<option value="DC">District Of Columbia</option>
								<option value="FL">Florida</option>
								<option value="GA">Georgia</option>
								<option value="HI">Hawaii</option>
								<option value="ID">Idaho</option>
								<option value="IL">Illinois</option>
								<option value="IN">Indiana</option>
								<option value="IA">Iowa</option>
								<option value="KS">Kansas</option>
								<option value="KY">Kentucky</option>
								<option value="LA">Louisiana</option>
								<option value="ME">Maine</option>
								<option value="MD">Maryland</option>
								<option value="MA">Massachusetts</option>
								<option value="MI">Michigan</option>
								<option value="MN">Minnesota</option>
								<option value="MS">Mississippi</option>
								<option value="MO">Missouri</option>
								<option value="MT">Montana</option>
								<option value="NE">Nebraska</option>
								<option value="NV">Nevada</option>
								<option value="NH">New Hampshire</option>
								<option value="NJ">New Jersey</option>
								<option value="NM">New Mexico</option>
								<option value="NY">New York</option>
								<option value="NC">North Carolina</option>
								<option value="ND">North Dakota</option>
								<option value="OH">Ohio</option>
								<option value="OK">Oklahoma</option>
								<option value="OR">Oregon</option>
								<option value="PA">Pennsylvania</option>
								<option value="RI">Rhode Island</option>
								<option value="SC">South Carolina</option>
								<option value="SD">South Dakota</option>
								<option value="TN">Tennessee</option>
								<option value="TX">Texas</option>
								<option value="UT">Utah</option>
								<option value="VT">Vermont</option>
								<option value="VA">Virginia</option>
								<option value="WA">Washington</option>
								<option value="WV">West Virginia</option>
								<option value="WI">Wisconsin</option>
								<option value="WY">Wyoming</option>
								
							</select><br>
							<div class="below">
								<input type="button" id="search" name="search" value="search" onclick="validateForm(); return false">
								<input type="button" id="clear" name="clear" value="clear" onclick="clearEverything()">
							</div>
						
					</div>
						
						
				</div>
				<div class="columns">
					<div class="cL">
						<input type="checkbox" id ="currentLocation" name="currentLocation" onclick="disableOthers()"><b class="cLextra">Current Location</b>
						</form>
					</div>
				</div>
				<div class="verticalLine"></div>
			</div>
			<div id="errorMsg">Please check the input address.</div>
			<div id="dynamicContent">
				<div class="dynamicCard">
				<?php  
					if($_SERVER['REQUEST_METHOD']=='POST' && $tester)
					{
						echo $card;
					}
				?>
				</div>
				<div class="dynamicTable">
				<?php  
					if($_SERVER['REQUEST_METHOD']=='POST' && $tester)
					{
						echo $table;
					}
				?>
				</div>
			</div>
		</div>


		<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
		<script type="text/javascript">
			function clearEverything()
			{
				document.getElementById("errorMsg").style.visibility="hidden";
				document.getElementById("dynamicContent").innerHTML="";
				document.forms["searchForm"].hiddenCity.value="";
				document.forms["searchForm"].hiddenLat.value="";
				document.forms["searchForm"].hiddenLon.value="";
				document.forms["searchForm"].street.value="";
				document.forms["searchForm"].city.value="";
				document.forms["searchForm"].state.value="default";

				if(document.getElementById("currentLocation").checked==true)
				{
					document.forms["searchForm"].currentLocation.checked=false;
					document.getElementById("street").disabled = false;
					document.getElementById("city").disabled = false;
					document.getElementById("state").disabled = false;
				}

	          		
			}
			function newText(a)
			{
				b=a.getAttribute('id');
				if(b==1)
				{
					document.getElementById("dynamicContent").innerHTML="<?php echo isset($cards)?$cards[1]:'' ?>";
					googlechart=<?php echo isset($googlechart)?json_encode($googlechart[1]):'A'; ?>;
				}
				else if(b==2)
				{
					document.getElementById("dynamicContent").innerHTML="<?php echo isset($cards)?$cards[2]:'' ?>";
					googlechart=<?php echo isset($googlechart)?json_encode($googlechart[2]):'A'; ?>;
				}
				else if(b==3)
				{
					document.getElementById("dynamicContent").innerHTML="<?php echo isset($cards)?$cards[3]:'' ?>";
					googlechart=<?php echo isset($googlechart)?json_encode($googlechart[3]):'A'; ?>;
				}
				else if(b==4)
				{
					document.getElementById("dynamicContent").innerHTML="<?php echo isset($cards)?$cards[4]:'' ?>";
					googlechart=<?php echo isset($googlechart)?json_encode($googlechart[4]):'A'; ?>;
				}
				else if(b==5)
				{
					document.getElementById("dynamicContent").innerHTML="<?php echo isset($cards)?$cards[5]:'' ?>";
					googlechart=<?php echo isset($googlechart)?json_encode($googlechart[5]):'A'; ?>;
				}
				else if(b==6)
				{
					document.getElementById("dynamicContent").innerHTML="<?php echo isset($cards)?$cards[6]:'' ?>";
					googlechart=<?php echo isset($googlechart)?json_encode($googlechart[6]):'A'; ?>;
				}
				else if(b==7)
				{
					document.getElementById("dynamicContent").innerHTML="<?php echo isset($cards)?$cards[7]:'' ?>";
					googlechart=<?php echo isset($googlechart)?json_encode($googlechart[7]):'A'; ?>;
				}
				else
				{
					document.getElementById("dynamicContent").innerHTML="<?php echo isset($cards)?$cards[8]:'' ?>";
					googlechart=<?php echo isset($googlechart)?json_encode($googlechart[8]):'A'; ?>;
				}
				
			}

			function loadimage(v)
			{
				x=v.getAttribute('src');
				if(x=="https://cdn4.iconfinder.com/data/icons/geosm-e-commerce/18/point-down-512.png")
				{
					v.setAttribute("src","https://cdn0.iconfinder.com/data/icons/navigation-set-arrows-part-one/32/ExpandLess-512.png");
					document.getElementById('curve_chart').style.width="700px";
					document.getElementById('curve_chart').style.height="190px";
					chartHelper();
				}
				else
				{
					v.setAttribute("src","https://cdn4.iconfinder.com/data/icons/geosm-e-commerce/18/point-down-512.png");
					document.getElementById('curve_chart').innerHTML="";
					document.getElementById('curve_chart').style.width="0px";
					document.getElementById('curve_chart').style.height="40px";
				}
			}

			function chartHelper()
			{
			    google.charts.load('current', {'packages':['corechart']});
			    google.charts.setOnLoadCallback(drawChart);
			}

			function drawChart() 
			{

		        var data = new google.visualization.DataTable();
		        data.addColumn('number', 'Temperature');
		        data.addColumn('number', 'T');
		        

		        for(i=0;i<24;i++)
		        {
		        	data.addRow([i,googlechart[i]]);
		        }

		       var options = {
		          title: '',
		          curveType: 'function',
		          series: {0:{color:'#93CBD8'}},
		          vAxis:{
		          	textPosition: 'none'
		          },

		        vAxes: {
		        
		          0: {title: 'Temperature'}
		        },
		        hAxes:{
		        	0: {title: 'Time'}
		        }
		        };

		        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

		        chart.draw(data, options);
		    }

			function disableOthers()
			{
				if(document.getElementById("currentLocation").checked==true)
				{
					document.getElementById("street").disabled = true;
					document.getElementById("street").value="";
					document.getElementById("city").disabled = true;
					document.getElementById("city").value="";
					document.getElementById("state").disabled = true;
					document.getElementById("state").value="default";
				}
				else
				{
					document.getElementById("street").disabled = false;
					document.getElementById("city").disabled = false;
					document.getElementById("state").disabled = false;
				}
			}

			function validateForm()
			{
				//if field not disabled and any one value empty or not selected 
				if(document.getElementById("street").disabled == false && (document.searchForm.street.value=="" || document.searchForm.city.value=="" || document.searchForm.state.options[document.searchForm.state.selectedIndex].value=="default") )
				{
					
					document.getElementById("errorMsg").style.visibility="visible";
					document.getElementById("dynamicContent").innerHTML="";
				
				}
				else if(document.getElementById("currentLocation").checked==true)
				{
					xmlhttp=new XMLHttpRequest();
			  		xmlhttp.open("GET","http://ip-api.com/json",false);
				  	xmlhttp.send();
	          		response = xmlhttp.responseText;
	          		jsonObj= JSON.parse(response);

	          		document.getElementById("hiddenLon").setAttribute('value',jsonObj.lon);
	          		document.getElementById("hiddenLat").setAttribute('value',jsonObj.lat);
	          		document.getElementById("hiddenCity").setAttribute('value',jsonObj.city);

		  			document.forms["searchForm"].submit();
				}
				else
				{
					document.forms["searchForm"].submit();

				}

			}
			document.getElementById('state').selectedIndex="<?php echo isset($_POST['state'])?array_search($_POST["state"],array_keys($map)):0 ?>";
			document.getElementById('currentLocation').checked="<?php echo isset($_POST['currentLocation'])?true:false ?>";
			disableOthers();
			document.getElementById('errorMsg').style.visibility="<?php if(!$tester){echo 'visible';} ?>";

		</script>

	</body>
</html>