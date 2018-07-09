<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no"/>
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=3VtaMWkKuR367HDWhEGok2Ik"></script>
    <script type="text/javascript" src="http://api.map.baidu.com/library/Heatmap/2.0/src/Heatmap_min.js"></script>
    <title>职位分布图</title>
    <style type="text/css">
        html {
            height: 100%;
        }

        body {
            margin: 0;
            height: 100%
        }

        #container {
            min-height: 100%;
            width: 100%;
        }
    </style>
</head>
<body>
<div id="container"></div>
</body>
</html>
@php
    $points = [];
    $file = fopen(storage_path('app/public') ."/positions_php.csv","r");
    while(! feof($file))
    {
        $item = fgetcsv($file);
        $points[] = ['lng'=>$item['23'],'lat'=>$item['24'],'count'=>1];
    }
    fclose($file);
@endphp

<script type="text/javascript">
    var map = new BMap.Map("container");          // 创建地图实例

    var point = new BMap.Point(116.418261, 30.921984);
    map.centerAndZoom(point, 5);             // 初始化地图，设置中心点坐标和地图级别
    map.enableScrollWheelZoom(); // 允许滚轮缩放

    var data = {!! json_encode($points,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE) !!};

    var options = {
        size: BMAP_POINT_SIZE_SMALLER,
        color: '#ff1f29'
    }
    var points = [];  // 添加海量点数据
    for (var i = 0; i < data.length; i++) {
        points.push(new BMap.Point(data[i]['lng'], data[i]['lat']));
    }
    var pointCollection = new BMap.PointCollection(points,options);  // 初始化PointCollection
    map.addOverlay(pointCollection);  // 添加Overlay

</script>