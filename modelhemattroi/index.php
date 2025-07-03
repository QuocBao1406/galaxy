<?php
session_start();
$loggedIn = isset($_SESSION['username']);
?>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/galaxy/lang.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ Mặt Trời</title>
     <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Exo+2:wght@400;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="/galaxy/css/header.css">
    <link rel="icon" href="Assets/Images/favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@400;700&family=Noto+Sans:wght@400;600&family=Poppins:wght@300;400;600;700&display=swap&subset=vietnamese" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@400;600&display=swap">
    <link rel="stylesheet" href="/galaxy/css/model3dhmt.css">
</head>
 <header id="head"> <div class="logo-container">
    <img src="/galaxy/images-icon/logo3.png" alt="logonhom" class="logo-overlay">
</div>
       <div id="menuhead">
        
        <nav>
           <button id="menu-toggle" aria-label="Mở menu">☰</button>

        <ul id="main-menu">
    <li><a href="/galaxy/trangchu.php" class="active"><img src="/galaxy/images-icon/home.png" alt=""><?= t('1') ?></a></li>

    <li class="dropdown">
        <a href="#"><img src="/galaxy/images-icon/hemattroi.png" alt=""><?= t('2') ?></a>
        <div class="dropdown-content">
            <a class="item" href="/galaxy/hemattroi/mattroi.php"><img src="/galaxy/images-icon/sun.png" alt=""><?= t('2,1') ?></a>
            <a class="item" href="/galaxy/hemattroi/saothuy.php"><img src="/galaxy/images-icon/mercury.png" alt=""><?= t('2,2') ?></a>
            <a class="item" href="/galaxy/hemattroi/saokim.php"><img src="/galaxy/images-icon/venus.png" alt=""><?= t('2,3') ?></a>
            <a class="item" href="/galaxy/hemattroi/traidat.php"><img src="/galaxy/images-icon/earth.png" alt=""><?= t('2,4') ?></a>
            <a class="item" href="/galaxy/hemattroi/mattrang.php"><img src="/galaxy/images-icon/full-moon.png" alt=""><?= t('2,5') ?> </a>
            <a class="item" href="/galaxy/hemattroi/saohoa.php"><img src="/galaxy/images-icon/mars.png" alt=""><?= t('2,6') ?></a>
            <a class="item" href="/galaxy/hemattroi/saomoc.php"><img src="/galaxy/images-icon/jupiter.png" alt=""><?= t('2,7') ?></a>
            <a class="item" href="/galaxy/hemattroi/saotho.php"><img src="/galaxy/images-icon/saturn.png" alt=""><?= t('2,8') ?></a>
            <a class="item" href="/galaxy/hemattroi/saothienvuong.php"><img src="/galaxy/images-icon/uranus.png" alt=""><?= t('2,9') ?></a>
            <a class="item" href="/galaxy/hemattroi/saohaivuong.php"><img src="/galaxy/images-icon/neptune.png" alt=""><?= t('2,10') ?></a>
        </div>
    </li>

    <li class="dropdown">
        <a href="#"><img src="/galaxy/images-icon/black-hole.png" alt=""><?= t('3') ?></a>
        <div class="dropdown-content">
            <a class="item" href="/galaxy/sukien.php"><img src="/galaxy/images-icon/sukien.png" alt=""><?= t('3,1') ?> </a>
            <a class="item" href="/galaxy/tintuc.php"><img src="/galaxy/images-icon/news.png" alt=""><?= t('3,2') ?> </a>
        </div>
    </li> <li><a href="/galaxy/congdong.php"><img src="/galaxy/images-icon/group (1).png" alt=""><?= t('4') ?></a></li>

    <li>
        <a href="<?php echo $loggedIn ? '/galaxy/taikhoan.php' : '/galaxy/TAIKHOAN/login-register.html'; ?>">
            <img src="/galaxy/images-icon/dangnhap.png" alt=""><?= t('5') ?>
        </a>
    </li>

    <li class="dropdown">
        <a href="#"><img src="/galaxy/images-icon/more.png" alt=""><?= t('6') ?></a>
        <div class="dropdown-content" style="left: -170%">
            <a class="item" href="/galaxy/vechungtoi.php"><img src="/galaxy/images-icon/group.png" alt=""><?= t('6,1') ?></a>
            <a class="language-switcher-container">
        <input type="checkbox" id="lang-toggle" class="lang-toggle-checkbox"
               <?php if(isset($current_lang)) echo ($current_lang == 'en') ? 'checked' : ''; ?>
        >
        <label for="lang-toggle" class="lang-toggle-label">
            <span class="lang-toggle-inner"></span>
            <span class="lang-toggle-switch"></span>
        </label>
             </a>
        </div>
    </li> 

</ul>
        </nav></div>
</header>

<body>
    <div id="controls-container" style="padding-top: 450px;">
        <button id="playPauseButton">Dừng</button>
    </div>
    
    <div id="object-info-box"></div>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script type="importmap">
        {
            "imports": {
                "three": "https://unpkg.com/three@0.164.1/build/three.module.js",
                "three/addons/": "https://unpkg.com/three@0.164.1/examples/jsm/"
            }
        }
    </script>

    <script type="module">
        import * as THREE from 'three';
        import { OrbitControls } from 'three/addons/controls/OrbitControls.js';

        // ... (Toàn bộ phần code JavaScript từ ví dụ trước của bạn giữ nguyên đến hàm displayInfo) ...
        // (Mình sẽ chỉ viết lại hàm displayInfo và các phần liên quan trực tiếp)

        let scene, camera, renderer, controls;
        const clickableObjects = [];
        const planetMeshes = {};
        const planetOrbitAngles = {};

        const raycaster = new THREE.Raycaster();
        const mouse = new THREE.Vector2();
        const infoBox = document.getElementById('object-info-box');
        const textureLoader = new THREE.TextureLoader();

        let highlightMesh, selectedObject = null;
        let isAnimating = true;
        const playPauseButton = document.getElementById('playPauseButton');

        const artisticRadii = {
            Sun: 4.0, Mercury: 0.28, Venus: 0.49, Earth: 0.525, Mars: 0.35,
            Jupiter: 1.96, Saturn: 1.68, Uranus: 1.05, Neptune: 0.98,
            Moon: 0.525 * 0.2726
        };

        const planetData = [
            // QUAN TRỌNG: BẠN CẦN ĐIỀN CÁC LINK EMBED CỦA NASA VÀO apiModelPlaceholder
            { name: "Sao Thủy", nameEng: "Mercury", texturePlaceholder: "saothuy.jpg", apiModelPlaceholder: "https://solarsystem.nasa.gov/gltf_embed/2369/?fs=true",
              modelRadius: artisticRadii.Mercury, orbitalRadius: 7, orbitalSpeed: 0.0048, rotationSpeed: 0.002,
              info: "Hành tinh nhỏ nhất." },
            { name: "Sao Kim", nameEng: "Venus", texturePlaceholder: "saokim.jpg", apiModelPlaceholder:"https://solarsystem.nasa.gov/gltf_embed/2342/?fs=true",
              modelRadius: artisticRadii.Venus, orbitalRadius: 9.5, orbitalSpeed: 0.0035, rotationSpeed: 0.001,
              info: "Nóng nhất Hệ Mặt Trời." },
            { name: "Trái Đất", nameEng: "Earth", texturePlaceholder: "traidat.jpg", apiModelPlaceholder: "https://solarsystem.nasa.gov/gltf_embed/2393/?fs=true", // Link ví dụ của bạn
              modelRadius: artisticRadii.Earth, orbitalRadius: 12.5, orbitalSpeed: 0.0020, rotationSpeed: 0.005,
              info: "Ngôi nhà của chúng ta." },
            { name: "Sao Hỏa", nameEng: "Mars", texturePlaceholder: "saohoa.jpg", apiModelPlaceholder: "https://solarsystem.nasa.gov/gltf_embed/ID_SAO_HOA/",
              modelRadius: artisticRadii.Mars, orbitalRadius: 16, orbitalSpeed: 0.0015, rotationSpeed: 0.0048,
              info: "Hành tinh Đỏ." },
            { name: "Sao Mộc", nameEng: "Jupiter", texturePlaceholder: "saomoc.jpg", apiModelPlaceholder: "https://solarsystem.nasa.gov/gltf_embed/2375/?fs=true",
              modelRadius: artisticRadii.Jupiter, orbitalRadius: 23, orbitalSpeed: 0.0008, rotationSpeed: 0.012,
              info: "Hành tinh lớn nhất." },
            { name: "Sao Thổ", nameEng: "Saturn", texturePlaceholder: "saotho.jpg", ringTexturePlaceholder: "vongsaotho.png", apiModelPlaceholder: "https://solarsystem.nasa.gov/gltf_embed/2355/?fs=true",
              modelRadius: artisticRadii.Saturn, orbitalRadius: 29, orbitalSpeed: 0.0005, rotationSpeed: 0.011,
              info: "Nổi tiếng với vành đai." },
            { name: "Sao Thiên Vương", nameEng: "Uranus", texturePlaceholder: "saothienvuong.jpg", apiModelPlaceholder: "https://solarsystem.nasa.gov/gltf_embed/2344/?fs=true",
              modelRadius: artisticRadii.Uranus, orbitalRadius: 34, orbitalSpeed: 0.0003, rotationSpeed: 0.006,
              info: "Hành tinh băng khổng lồ." },
            { name: "Sao Hải Vương", nameEng: "Neptune", texturePlaceholder: "saohaivuong.jpg", apiModelPlaceholder: "https://solarsystem.nasa.gov/gltf_embed/2364/?fs=true",
              modelRadius: artisticRadii.Neptune, orbitalRadius: 39, orbitalSpeed: 0.0002, rotationSpeed: 0.0055,
              info: "Hành tinh xa Mặt Trời nhất." }
        ];

        let sunMesh, moonMesh;
        let moonOrbitAngle = 0;
        const moonOrbitRadius = artisticRadii.Earth * 2.0;
        const moonOrbitSpeed = 0.01;
        const moonRotationSpeed = 0.003;

        function init() {
            scene = new THREE.Scene();
            scene.background = new THREE.Color(0x000000);
            // textureLoader.load("/galaxy/images-icon/taikhoan.jpg", function (texture) {
            //     scene.background = texture;
            // }, undefined, function (err) { console.error('Không tải được ảnh nền:', err); });

            camera = new THREE.PerspectiveCamera(50, window.innerWidth / window.innerHeight, 0.1, 2000);
            // ===================================
    // BẮT ĐẦU CODE TẠO BẦU TRỜI SAO
    // ===================================
    const starVertices = [];
    for (let i = 0; i < 10000; i++) {
        const x = (Math.random() - 0.5) * 2000;
        const y = (Math.random() - 0.5) * 2000;
        const z = (Math.random() - 0.5) * 2000;
        starVertices.push(x, y, z);
    }

    const starGeometry = new THREE.BufferGeometry();
    starGeometry.setAttribute('position', new THREE.Float32BufferAttribute(starVertices, 3));

    const starMaterial = new THREE.PointsMaterial({
        color: 0xffffff, // Màu của sao
        size: 0.3,       // Kích thước của mỗi chấm sao
        sizeAttenuation: true // Các ngôi sao ở xa sẽ nhỏ hơn
    });

    const stars = new THREE.Points(starGeometry, starMaterial);
    scene.add(stars);
    // ===================================
    // KẾT THÚC CODE TẠO BẦU TRỜI SAO
    // ===================================

    renderer = new THREE.WebGLRenderer({ antialias: true });
            camera.position.set(0, 25, 65);
            camera.lookAt(0,0,0);

            renderer = new THREE.WebGLRenderer({ antialias: true });
            renderer.setSize(window.innerWidth, window.innerHeight);
            document.body.appendChild(renderer.domElement);

            const ambientLight = new THREE.AmbientLight(0xffffff, 0.6);
            scene.add(ambientLight);
            const pointLight = new THREE.PointLight(0xffffff, 2.8, 1500);
            scene.add(pointLight);

            if (playPauseButton) {
                playPauseButton.addEventListener('click', () => {
                    isAnimating = !isAnimating;
                    playPauseButton.textContent = isAnimating ? 'Dừng' : 'Quay';
                });
            }

            // Tạo Mặt Trời
            const sunGeometry = new THREE.SphereGeometry(artisticRadii.Sun, 64, 64);
            let sunMaterial;
            sunMaterial = new THREE.MeshBasicMaterial({
                map: textureLoader.load("mattroi.jpg", undefined, undefined, (err) => {
                    console.warn("Không tải được texture Mặt Trời. Dùng màu cơ bản.");
                    if (sunMaterial) { sunMaterial.map = null; sunMaterial.color.set(0xffcc00); }
                })
            });
            sunMesh = new THREE.Mesh(sunGeometry, sunMaterial);
            // QUAN TRỌNG: Thêm apiModelPlaceholder cho Mặt Trời
            sunMesh.userData = { name: "Mặt Trời", info: "Ngôi sao trung tâm.", apiModelPlaceholder: "https://solarsystem.nasa.gov/gltf_embed/2352/?fs=true" };
            scene.add(sunMesh);
            clickableObjects.push(sunMesh);
            pointLight.position.copy(sunMesh.position);

            // Tạo các hành tinh
            planetData.forEach(data => {
                const geometry = new THREE.SphereGeometry(data.modelRadius, 32, 32);
                let material;
                material = new THREE.MeshStandardMaterial({
                    map: textureLoader.load(data.texturePlaceholder, undefined, undefined, () => {
                        console.warn(`Không tải được texture cho ${data.name}. Dùng màu cơ bản.`);
                        if (material) { material.map = null; material.color.set(Math.random() * 0xffffff); }
                    }),
                    roughness: 0.8, metalness: 0.1
                });
                const planet = new THREE.Mesh(geometry, material);
                planet.userData = { name: data.name, info: data.info, apiModelPlaceholder: data.apiModelPlaceholder }; // Đã có từ planetData
                scene.add(planet);
                clickableObjects.push(planet);
                planetMeshes[data.nameEng] = planet;
                planetOrbitAngles[data.nameEng] = Math.random() * Math.PI * 2;

                if (data.nameEng === "Saturn" && data.ringTexturePlaceholder) {
                    const ringInnerRadius = data.modelRadius * 1.2;
                    const ringOuterRadius = data.modelRadius * 2.2;
                    const ringGeometry = new THREE.RingGeometry(ringInnerRadius, ringOuterRadius, 64);
                    let ringMaterial;
                    ringMaterial = new THREE.MeshBasicMaterial({
                        map: textureLoader.load(data.ringTexturePlaceholder, undefined, undefined, () => {
                             console.warn(`Không tải được texture vành đai Sao Thổ. Vành đai sẽ có màu xám.`);
                             if (ringMaterial) { ringMaterial.map = null; ringMaterial.color.set(0xaaaaaa); }
                        }),
                        side: THREE.DoubleSide, transparent: true, opacity: 0.9
                    });
                    const ringMesh = new THREE.Mesh(ringGeometry, ringMaterial);
                    ringMesh.rotation.x = Math.PI / 2.3;
                    planet.add(ringMesh);
                }
            });

            // Tạo Mặt Trăng
            let moonMaterial;
            const moonTexture = textureLoader.load("mattrang.jpg", undefined, undefined, () => {
                console.warn(`Không tải được texture Mặt Trăng. Dùng màu cơ bản.`);
                if (moonMaterial) { moonMaterial.map = null; moonMaterial.color.set(0x888888); }
            });
            moonMaterial = new THREE.MeshStandardMaterial({ map: moonTexture, roughness: 0.9 });
            const moonGeometry = new THREE.SphereGeometry(artisticRadii.Moon, 32, 32);
            moonMesh = new THREE.Mesh(moonGeometry, moonMaterial);
            // QUAN TRỌNG: Thêm apiModelPlaceholder cho Mặt Trăng
            moonMesh.userData = { name: "Mặt Trăng", info: "Vệ tinh tự nhiên của Trái Đất. (Kích thước & khoảng cách minh họa).", apiModelPlaceholder: "https://solarsystem.nasa.gov/gltf_embed/2366/?fs=true" };
            scene.add(moonMesh);
            clickableObjects.push(moonMesh);

            const highlightGeometry = new THREE.SphereGeometry(1, 48, 48);
            const highlightMaterial = new THREE.MeshBasicMaterial({
                color: 0xffff00, transparent: true, opacity: 0.35, depthWrite: false
            });
            highlightMesh = new THREE.Mesh(highlightGeometry, highlightMaterial);
            highlightMesh.visible = false;
            scene.add(highlightMesh);

            controls = new OrbitControls(camera, renderer.domElement);
            controls.enableDamping = true;
            controls.dampingFactor = 0.05;
            controls.minDistance = 1;
            controls.maxDistance = 150;

            renderer.domElement.addEventListener('click', onMouseClick, false);
            window.addEventListener('resize', onWindowResize, false);

            animate();
        }

        function onMouseClick(event) {
            event.preventDefault();
            mouse.x = (event.clientX / window.innerWidth) * 2 - 1;
            mouse.y = - (event.clientY / window.innerHeight) * 2 + 1;
            raycaster.setFromCamera(mouse, camera);
            const intersects = raycaster.intersectObjects(clickableObjects);

            if (intersects.length > 0) {
                selectedObject = intersects[0].object;
                displayInfo(selectedObject.userData); // Gọi hàm displayInfo mới
                highlightMesh.position.copy(selectedObject.position);
                const objectRadius = selectedObject.geometry.parameters.radius;
                highlightMesh.scale.setScalar(objectRadius * 1.30);
                highlightMesh.visible = true;
            } else {
                hideInfo();
            }
        }

        // **** HÀM DISPLAYINFO ĐƯỢC CẬP NHẬT ****
        function displayInfo(data) {
            if (data && data.name) {
                let infoHTML = `<h3>${data.name}</h3>`;
                infoHTML += `<p>${data.info}</p>`; // Hiển thị thông tin mô tả cơ bản trước

                if (data.apiModelPlaceholder) {
                    // Kiểm tra xem placeholder có phải là một URL hợp lệ không
                    if (data.apiModelPlaceholder.startsWith('http')) {
                        infoHTML += `<p style="margin-top:10px;"><strong>Mô hình 3D từ NASA:</strong></p>`;
                        // Tùy chọn 1: Nhúng bằng iframe (có thể bị chặn)
                        infoHTML += `<iframe src="${data.apiModelPlaceholder}" title="Mô hình 3D ${data.name}" style="width:100%; height:200px; border:1px solid #555; margin-top:5px;"></iframe>`;
                        // Tùy chọn 2: Hoặc chỉ hiển thị liên kết (an toàn hơn)
                        // infoHTML += `<p><a href="${data.apiModelPlaceholder}" target="_blank" style="color: #aaccff;">Xem mô hình chi tiết (NASA)</a></p>`;
                    } else {
                        // Nếu không phải URL, chỉ hiển thị placeholder text
                        infoHTML += `<p><strong>API Model ID:</strong> ${data.apiModelPlaceholder}</p>`;
                    }
                }
                infoBox.innerHTML = infoHTML;
                infoBox.style.display = 'block';
            } else {
                 hideInfo();
            }
        }

        function hideInfo() {
            infoBox.innerHTML = ''; // Xóa nội dung cũ, bao gồm cả iframe nếu có
            infoBox.style.display = 'none';
            if (highlightMesh) {
                highlightMesh.visible = false;
            }
            selectedObject = null;
        }

        function onWindowResize() {
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
        }

        function animate() {
            requestAnimationFrame(animate);
            if (isAnimating) {
                sunMesh.rotation.y += 0.0005;
                planetData.forEach(data => {
                    const planet = planetMeshes[data.nameEng];
                    if (planet) {
                        planet.rotation.y += data.rotationSpeed;
                        planetOrbitAngles[data.nameEng] += data.orbitalSpeed;
                        planet.position.x = Math.cos(planetOrbitAngles[data.nameEng]) * data.orbitalRadius;
                        planet.position.z = Math.sin(planetOrbitAngles[data.nameEng]) * data.orbitalRadius;
                    }
                });
                const earthMesh = planetMeshes["Earth"];
                if (earthMesh && moonMesh) {
                    moonMesh.rotation.y += moonRotationSpeed;
                    moonOrbitAngle += moonOrbitSpeed;
                    moonMesh.position.x = earthMesh.position.x + Math.cos(moonOrbitAngle) * moonOrbitRadius;
                    moonMesh.position.z = earthMesh.position.z + Math.sin(moonOrbitAngle) * moonOrbitRadius;
                    moonMesh.position.y = earthMesh.position.y;
                }
                if (highlightMesh.visible && selectedObject) {
                    highlightMesh.position.copy(selectedObject.position);
                }
            }
            controls.update();
            renderer.render(scene, camera);
        }
        init();
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const menuToggle = document.getElementById('menu-toggle');
        const mainMenu = document.getElementById('main-menu');

        // --- Code cũ: Xử lý đóng/mở menu chính ---
        if (menuToggle && mainMenu) {
            menuToggle.addEventListener('click', function() {
                mainMenu.classList.toggle('is-open');

                if (mainMenu.classList.contains('is-open')) {
                    this.innerHTML = '✖';
                    this.setAttribute('aria-label', 'Đóng menu');
                } else {
                    this.innerHTML = '☰';
                    this.setAttribute('aria-label', 'Mở menu');
                }
            });
             // === CODE MỚI CHO CÔNG TẮC NGÔN NGỮ ===
    const langToggle = document.getElementById('lang-toggle');

    if (langToggle) {
        langToggle.addEventListener('change', function() {
            if (this.checked) {
                // Nếu công tắc được BẬT, chuyển sang Tiếng Anh
                window.location.href = '?lang=en';
            } else {
                // Nếu công tắc được TẮT, chuyển về Tiếng Việt
                window.location.href = '?lang=vi';
            }
        });
    }
    // ===================================
        }

        // --- Code MỚI: Xử lý đóng/mở các menu con ---
        const dropdownItems = document.querySelectorAll('#main-menu .dropdown, #main-menu .dropdown2');

        dropdownItems.forEach(function(item) {
            // Lấy thẻ <a> là con trực tiếp của li.dropdown
            const link = item.querySelector('a');

            link.addEventListener('click', function(event) {
                // Chỉ hoạt động khi nút 3 gạch đang hiển thị (tức là trên di động)
                if (window.getComputedStyle(menuToggle).display !== 'none') {
                    // Ngăn không cho trình duyệt nhảy trang khi bấm vào mục cha
                    event.preventDefault();
                    // Thêm/xóa class để đóng/mở menu con
                    item.classList.toggle('submenu-open');
                }
            });
        });
    });
</script>

</body>
</html>