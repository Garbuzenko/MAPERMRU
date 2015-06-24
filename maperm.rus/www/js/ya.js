var layers = {
        data : [],
        init : function(mapDivId, longitude, latitude, zoom) {
            // ya map:
            this.map = new YMaps.Map(YMaps.jQuery(mapDivId)[0]);
            this.map.setCenter(new YMaps.GeoPoint(longitude, latitude), zoom, YMaps.MapType.MAP);
            this.map.addControl(new YMaps.Zoom());
            this.map.addControl(new YMaps.ToolBar());
            this.map.addControl(new YMaps.ScaleLine());
            this.map.enableScrollZoom({smooth:true});
            this.map.enableHotKeys();
            //search
            (new YMaps.SearchControl({
                useMapBounds:true,
                noCentering:false,
                noPlacemark:false,
                resultsPerPage:5,
                width:400
            })).onAddToMap(this.map, new YMaps.ControlPosition(YMaps.ControlPosition.TOP_RIGHT, new YMaps.Point(0, 0)));
            // layer:
            var layerName = this.data[0].name;
            layers.show(layerName);
        },
        // creates overlay from object description
        createMapOverlay : function(objectDesc) {
            //type, point, style, description
            var points = objectDesc.points;
            if (points.length > 0) {
                for (var i = 0; i < points.length; i++) {
                    points[i] = new YMaps.GeoPoint(points[i].lng, points[i].lat);
                }
            } else {
                points = new YMaps.GeoPoint(points.lng, points.lat);
            }
            if (points.length == 0)
                return false;
            var allowObjects = ["Placemark", "Polyline", "Polygon"],
                    index = YMaps.jQuery.inArray(objectDesc.type, allowObjects),
                    constructor = allowObjects[(index == -1) ? 0 : index];
            var description = objectDesc.description || "";
            var object = new YMaps[constructor](points, {style: objectDesc.style, hasBalloon : !!description, hasHint: !!objectDesc.name});
            object.description = description;
            object.name = objectDesc.name;
            return object;
        },
        // constructs new layer from description and adds it
        add : function(layerDesc) {
            var layer = {name: layerDesc.name, center: layerDesc.center, content:[]};
            // import styles
            for (var i = 0; i < layerDesc.styles.length; i++) {
                YMaps.Styles.add(layerDesc.styles[i].name, layerDesc.styles[i].style);
            }
            // import objects
            for (var i = 0; i < layerDesc.objects.length; i++) {
                var o = this.createMapOverlay(layerDesc.objects[i]);
                if (o)
                    layer.content[layer.content.length] = o;
            }
            this.data[this.data.length] = layer;
        },
        // gets layer with name
        get : function(name) {
            for (var i = 0; i < this.data.length; i++)
                if (this.data[i].name == name)
                return this.data[i];
                    
        },
        // show layer with name
        show : function(name) {
            var layer = this.get(name);
            if (layer.show) return;
            for (var i = 0; i < layer.content.length; i++)
                this.map.addOverlay(layer.content[i]);
            layer.show = true;
            var point = new YMaps.GeoPoint(layer.center.lng, layer.center.lat);
            this.map.setZoom(layer.center.zoom, {smooth:true,position:point, centering:true});
        }
    };