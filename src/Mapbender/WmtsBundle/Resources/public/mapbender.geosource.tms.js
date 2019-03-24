window.Mapbender = Mapbender || {};
window.Mapbender.TmsSource = (function() {
    function TmsSource(definition) {
        Mapbender.WmtsTmsBaseSource.apply(this, arguments);
    }
    TmsSource.prototype = Object.create(Mapbender.WmtsTmsBaseSource.prototype);
    $.extend(TmsSource.prototype, {
        constructor: TmsSource,
        _initializeSingleCompatibleLayer: function(compatibleLayer, srsName) {
            var matrixSet = this.getMatrixSetByIdent(compatibleLayer.options.tilematrixset);
            var options = this._getNativeLayerOptions(matrixSet, compatibleLayer, srsName);
            return new OpenLayers.Layer.TMS(compatibleLayer.options.title, compatibleLayer.options.tileUrls, options);
        },
        _getNativeLayerOptions: function(matrixSet, compatibleLayer, srsName) {
            var parentValues = Mapbender.WmtsTmsBaseSource.prototype._getNativeLayerOptions.apply(this, arguments);
            return $.extend(parentValues, {
                style: compatibleLayer.options.style,
                type: compatibleLayer.options.format.split('/').pop(),
                layername: compatibleLayer.options.identifier,
                serviceVersion: this.configuration.version,
                tileSize: new OpenLayers.Size(matrixSet.tileSize[0], matrixSet.tileSize[1])
            });
        },
        /**
         * @param {WmtsLayerConfig} layer
         * @param {WmtsTileMatrixSet} matrixSet
         * @param {String} srsName
         */
        _getMatrixOptions: function(layer, matrixSet, srsName) {
            var self = this;
            var options = {
                layername: layer.options.identifier,
                tileSize: new OpenLayers.Size(matrixSet.tileSize[0], matrixSet.tileSize[1]),
                serverResolutions: matrixSet.tilematrices.map(function(tileMatrix) {
                    return self._getMatrixResolution(tileMatrix, srsName);
                })
            };
            if (matrixSet.origin && matrixSet.origin.length) {
                options.tileOrigin = new OpenLayers.LonLat(matrixSet.origin[0], matrixSet.origin[1]);
            }
            return options;
        },
        /**
         * @param {WmtsTileMatrix} tileMatrix
         * @param {String} srsName
         * @return {Number}
         * @private
         */
        _getMatrixResolution: function(tileMatrix, srsName) {
            // Yes, seriously, it's called scaleDenominator but it's the resolution
            // @todo: resolve backend config wording weirdness
            return tileMatrix.scaleDenominator;
        },
        /**
         * @param {WmtsLayerConfig} layerDef
         * @return {string}
         */
        getPrintBaseUrl: function(layerDef) {
            return [layerDef.options.tileUrls[0], this.configuration.version, '/', layerDef.options.identifier].join('');
        }

    });
    Mapbender.Source.typeMap['tms'] = TmsSource;
    return TmsSource;
}());
