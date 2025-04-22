<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>عرض المنتج بالواقع المعزز</title>
    <script src="https://aframe.io/releases/1.4.0/aframe.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/AR-js-org/AR.js/aframe/build/aframe-ar.js"></script>
    <style>
        body {
            text-align: center;
            font-family: Arial, sans-serif;
        }
        .controls {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            justify-content: center;
            gap: 15px;
            z-index: 10;
        }
        .control-button {
            padding: 10px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .control-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <a-scene embedded arjs>
        <a-entity id="productEntity"
                    gltf-model="{{ asset('/storage/models/' . $product->model_url) }}" 
                    position="0 1 -3" scale="0.5 0.5 0.5" rotation="0 0 0" data-raycastable>
        </a-entity>

        <a-camera position="0 1.6 0" raycaster="objects: [data-raycastable]">
            <a-cursor></a-cursor>
        </a-camera>
    </a-scene>

    <!-- أزرار التحكم في التدوير، التحجيم والإزاحة -->
    <div class="controls">
        <button class="control-button" id="moveUp">Move Up</button>
        <button class="control-button" id="moveDown">Move Down</button>
        <button class="control-button" id="moveLeft">Move Left</button>
        <button class="control-button" id="moveRight">Move Right</button>
        <button class="control-button" id="rotateLeft">Rotate Left</button>
        <button class="control-button" id="rotateRight">Rotate Right</button>
        <button class="control-button" id="rotateUp">Rotate Up</button>
        <button class="control-button" id="rotateDown">Rotate Down</button>
        <button class="control-button" id="scaleUp">Scale Up</button>
        <button class="control-button" id="scaleDown">Scale Down</button>
        <button class="control-button" id="zoomIn">Zoom In</button>
        <button class="control-button" id="zoomOut">Zoom Out</button>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let entity = document.querySelector("#productEntity");
            let movementSpeed = 0.3;  // سرعة الإزاحة
            let rotationSpeed = 15   // سرعة التدوير (درجات)
            let scaleSpeed = 0.3;     // سرعة التحجيم

            // بعد تحميل النموذج
            entity.addEventListener("model-loaded", function () {
                let mesh = entity.getObject3D("mesh");

                if (mesh) {
                    mesh.traverse(node => {
                        if (node.isMesh && node.material) {
                            if (node.material.map) {
                                node.material.map.encoding = THREE.sRGBEncoding;
                                node.material.needsUpdate = true;
                            }
                        }
                    });
                }
            });

            // أزرار الإزاحة
            document.getElementById("moveUp").addEventListener("click", function () {
                let currentPosition = entity.getAttribute("position");
                entity.setAttribute("position", {
                    x: currentPosition.x,
                    y: currentPosition.y + movementSpeed,
                    z: currentPosition.z
                });
            });

            document.getElementById("moveDown").addEventListener("click", function () {
                let currentPosition = entity.getAttribute("position");
                entity.setAttribute("position", {
                    x: currentPosition.x,
                    y: currentPosition.y - movementSpeed,
                    z: currentPosition.z
                });
            });

            document.getElementById("moveLeft").addEventListener("click", function () {
                let currentPosition = entity.getAttribute("position");
                entity.setAttribute("position", {
                    x: currentPosition.x - movementSpeed,
                    y: currentPosition.y,
                    z: currentPosition.z
                });
            });

            document.getElementById("moveRight").addEventListener("click", function () {
                let currentPosition = entity.getAttribute("position");
                entity.setAttribute("position", {
                    x: currentPosition.x + movementSpeed,
                    y: currentPosition.y,
                    z: currentPosition.z
                });
            });

            // أزرار التدوير
            document.getElementById("rotateLeft").addEventListener("click", function () {
                let currentRotation = entity.getAttribute("rotation");
                entity.setAttribute("rotation", {
                    x: currentRotation.x,
                    y: currentRotation.y - rotationSpeed,
                    z: currentRotation.z
                });
            });

            document.getElementById("rotateRight").addEventListener("click", function () {
                let currentRotation = entity.getAttribute("rotation");
                entity.setAttribute("rotation", {
                    x: currentRotation.x,
                    y: currentRotation.y + rotationSpeed,
                    z: currentRotation.z
                });
            });

            document.getElementById("rotateUp").addEventListener("click", function () {
                let currentRotation = entity.getAttribute("rotation");
                entity.setAttribute("rotation", {
                    x: currentRotation.x - rotationSpeed,
                    y: currentRotation.y,
                    z: currentRotation.z
                });
            });

            document.getElementById("rotateDown").addEventListener("click", function () {
                let currentRotation = entity.getAttribute("rotation");
                entity.setAttribute("rotation", {
                    x: currentRotation.x + rotationSpeed,
                    y: currentRotation.y,
                    z: currentRotation.z
                });
            });

            // أزرار التحجيم
            document.getElementById("scaleUp").addEventListener("click", function () {
                let currentScale = entity.getAttribute("scale");
                entity.setAttribute("scale", {
                    x: currentScale.x + scaleSpeed,
                    y: currentScale.y + scaleSpeed,
                    z: currentScale.z + scaleSpeed
                });
            });

            document.getElementById("scaleDown").addEventListener("click", function () {
                let currentScale = entity.getAttribute("scale");
                entity.setAttribute("scale", {
                    x: currentScale.x - scaleSpeed,
                    y: currentScale.y - scaleSpeed,
                    z: currentScale.z - scaleSpeed
                });
            });

            // أزرار التكبير والتصغير
            document.getElementById("zoomIn").addEventListener("click", function () {
                let currentCameraPosition = document.querySelector("a-camera").getAttribute("position");
                document.querySelector("a-camera").setAttribute("position", {
                    x: currentCameraPosition.x,
                    y: currentCameraPosition.y,
                    z: currentCameraPosition.z - 0.4  // تكبير المشهد
                });
            });

            document.getElementById("zoomOut").addEventListener("click", function () {
                let currentCameraPosition = document.querySelector("a-camera").getAttribute("position");
                document.querySelector("a-camera").setAttribute("position", {
                    x: currentCameraPosition.x,
                    y: currentCameraPosition.y,
                    z: currentCameraPosition.z + 0.4  // تصغير المشهد
                });
            });
        });
    </script>

</body>
</html>
