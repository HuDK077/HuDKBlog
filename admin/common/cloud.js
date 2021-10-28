import { Detector } from "@/common/Detector.js";
import { THREE, GeometryUtils } from "@/common/three";

if (!window.requestAnimationFrame) {
  window.requestAnimationFrame = function () {
    return window.webkitRequestAnimationFrame ||
      window.mozRequestAnimationFrame ||
      window.oRequestAnimationFrame ||
      window.msRequestAnimationFrame ||
      function (callback, element) {
        console.log(">>>>");
        window.setTimeout(callback, 1e3 / 60);
      };
  }();
}

class CLOUD {
  constructor(id) {
    this.rootId = id;
    this.rootNode = "";
    this.container = "";
    this.camera = "";
    this.scene = "";
    this.renderer = "";
    this.sky = "";
    this.mesh = "";
    this.geometry = "";
    this.material = "";
    this.i = "";
    this.h = "";
    this.color = "";
    this.colors = [];
    this.sprite = "";
    this.size = "";
    this.x = "";
    this.y = "";
    this.z = "";
    this.mouseX = 0;
    this.mouseY = 0;
    this.start_time = new Date().getTime();
    this.windowHalfX = window.innerWidth / 2;
    this.windowHalfY = window.innerHeight / 2;
    this.startAnimals = false;
    this.mm = "";
    this.rs = "";
    this.temp = 0;
    this.vertexShader = "varying vec2 vUv; void main() { vUv = uv; gl_Position = projectionMatrix * modelViewMatrix * vec4( position, 1.0 ); }";
    this.fragmentShader = "uniform sampler2D map; uniform vec3 fogColor; uniform float fogNear; uniform float fogFar; varying vec2 vUv; void main() { float depth = gl_FragCoord.z / gl_FragCoord.w; float fogFactor = smoothstep( fogNear, fogFar, depth ); gl_FragColor = texture2D( map, vUv ); gl_FragColor.w *= pow( gl_FragCoord.z, 20.0 ); gl_FragColor = mix( gl_FragColor, vec4( fogColor, gl_FragColor.w ), fogFactor ); }";
  }
  // 初始化
  init() {
    this.rootNode = document.getElementById(this.rootId);
    this.initBG();

    this.container = document.createElement('div');
    this.rootNode.appendChild(this.container);
    this.camera = new THREE.Camera(30, window.innerWidth / window.innerHeight, 1, 3000);
    this.camera.position.z = 6000;
    this.scene = new THREE.Scene();
    this.geometry = new THREE.Geometry();
    let texture = THREE.ImageUtils.loadTexture("/image/cloud.png");
    texture.magFilter = THREE.LinearMipMapLinearFilter;
    texture.minFilter = THREE.LinearMipMapLinearFilter;
    let fog = new THREE.Fog(0x4584b4, -100, 3000);
    this.material = new THREE.MeshShaderMaterial({
      uniforms: {
        "map": {
          type: "t",
          value: 2,
          texture: texture
        },
        "fogColor": {
          type: "c",
          value: fog.color
        },
        "fogNear": {
          type: "f",
          value: fog.near
        },
        "fogFar": {
          type: "f",
          value: fog.far
        },
      },
      vertexShader: this.vertexShader,
      fragmentShader: this.fragmentShader,
      depthTest: false
    });
    let plane = new THREE.Mesh(new THREE.Plane(64, 64));
    for (let i = 0; i < 8000; i++) {
      plane.position.x = Math.random() * 1000 - 500;
      plane.position.y = -Math.random() * Math.random() * 200 - 15;
      plane.position.z = i;
      plane.rotation.z = Math.random() * Math.PI;
      plane.scale.x = plane.scale.y = Math.random() * Math.random() * 1.5 + 0.5;
      GeometryUtils.merge(this.geometry, plane)
    }
    this.mesh = new THREE.Mesh(this.geometry, this.material);
    this.scene.addObject(this.mesh);
    this.mesh = new THREE.Mesh(this.geometry, this.material);
    this.mesh.position.z = -8000;
    this.scene.addObject(this.mesh);
    this.renderer = new THREE.WebGLRenderer({
      antialias: false
    });
    this.renderer.setSize(window.innerWidth, window.innerHeight);
    this.container.appendChild(this.renderer.domElement);
    this.mm = (e) => { this._onDocumentMouseMove(e) };
    this.rs = (e) => { this._onWindowResize(e) };
    document.addEventListener('mousemove', this.mm, false);
    window.addEventListener('resize', this.rs, false)
  }
  // 绘制背景
  initBG() {
    if (!Detector.webgl) Detector.addGetWebGLMessage();
    let canvas = document.createElement('canvas');
    canvas.width = 32;
    canvas.height = window.innerHeight;
    let context = canvas.getContext('2d');
    let gradient = context.createLinearGradient(0, 0, 0, canvas.height);
    gradient.addColorStop(0, "#1e4877");
    gradient.addColorStop(0.5, "#4584b4");
    context.fillStyle = gradient;
    context.fillRect(0, 0, canvas.width, canvas.height);
    this.rootNode.style.background = 'url(' + canvas.toDataURL('image/png') + ')';
  }

  _onDocumentMouseMove(event) {
    this.mouseX = (event.clientX - this.windowHalfX) * 0.25;
    this.mouseY = (event.clientY - this.windowHalfY) * 0.15;
  }
  _onWindowResize() {
    this.camera.aspect = window.innerWidth / window.innerHeight;
    this.camera.updateProjectionMatrix();
    this.renderer.setSize(window.innerWidth, window.innerHeight)
  }
  _animate() {
    requestAnimationFrame(() => {
      if (!this.startAnimals) {
        return;
      }
      this._animate();
    });
    this._render()
  }
  _render() {
    let { position, camera, mouseX, mouseY, start_time, scene, renderer } = this;
    position = ((new Date().getTime() - start_time) * 0.03) % 8000;
    camera.position.x += (mouseX - camera.target.position.x) * 0.01;
    camera.position.y += (-mouseY - camera.target.position.y) * 0.01;
    camera.position.z = -position + 8000;
    camera.target.position.x = camera.position.x;
    camera.target.position.y = camera.position.y;
    camera.target.position.z = camera.position.z - 1000;
    renderer.render(scene, camera)
  }

  startAnimal() {
    this.startAnimals = true;
    this._animate();
  }
  stopAnimal() {
    this.startAnimals = false;
  }
  // 卸载
  unLoad() {
    console.log("unload");
    document.removeEventListener('mousemove', this.mm, false);
    window.removeEventListener('resize', this.rs, false);
    this.stopAnimal();
    delete this.camera;
    delete this.scene;
    delete this.geometry;
    delete this.material;
    delete this.mesh;
    delete this.renderer;
  }
}

export default CLOUD;