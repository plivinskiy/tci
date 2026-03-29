(function ($) {
    'use strict';
    function initWGLCurtains($context = $(document)) {
        if (typeof Curtains === 'undefined') return;
        window.wglCurtains = window.wglCurtains || [];
        window.wglCurtainsMobile = mobilecheck();

        function createNoiseDataURL(size = 256) {
            const canvas = document.createElement('canvas');
            canvas.width = canvas.height = size;
            const ctx = canvas.getContext('2d');
            const img = ctx.createImageData(size, size);
            const data = img.data;
            for (let i = 0; i < data.length; i += 4) {
                const val = Math.random() * 255;
                data[i] = data[i + 1] = data[i + 2] = val;
                data[i + 3] = 255;
            }
            ctx.putImageData(img, 0, 0);
            return canvas.toDataURL();
        }

        function mobilecheck() {
            if (window.hasOwnProperty('wglIsMobileCurtainsChecked')) {
                return window.wglIsMobileCurtainsChecked;
            }
            let check = false;
            const width = window.innerWidth || document.documentElement.clientWidth;
            check = width <= 1200;

            window.wglIsMobileCurtainsChecked = check;
            return check;
        }

        const VERT_DEFAULT = `
                precision mediump float;
                attribute vec3 aVertexPosition;
                attribute vec2 aTextureCoord;
                uniform mat4 uMVMatrix;
                uniform mat4 uPMatrix;
                varying vec2 vTextureCoord;
                void main() {
                    gl_Position = uPMatrix * uMVMatrix * vec4(aVertexPosition, 1.0);
                    vTextureCoord = aTextureCoord;
                }
            `;

        const VERT_WAVE3D = `
                precision mediump float;
                attribute vec3 aVertexPosition;
                attribute vec2 aTextureCoord;
                uniform mat4 uMVMatrix;
                uniform mat4 uPMatrix;
                uniform float uTime;
                uniform vec2 uResolution;
                uniform vec2 uMousePosition;
                uniform float uMouseMoveStrength;
                varying vec2 vTextureCoord;
                varying vec3 vVertexPosition;
                void main() {
                    vec3 vertexPosition = aVertexPosition;
                    float distanceFromMouse = distance(uMousePosition, vec2(vertexPosition.x, vertexPosition.y));
                    float waveSinusoid = cos(5.0 * (distanceFromMouse - (uTime / 75.0)));
                    float distanceStrength = (0.4 / (distanceFromMouse + 0.4));
                    float distortionEffect = distanceStrength * waveSinusoid * uMouseMoveStrength;
                    vertexPosition.z += distortionEffect / 30.0;
                    vertexPosition.x += (distortionEffect / 30.0 * (uResolution.x / uResolution.y) * (uMousePosition.x - vertexPosition.x));
                    vertexPosition.y += distortionEffect / 30.0 * (uMousePosition.y - vertexPosition.y);
                    gl_Position = uPMatrix * uMVMatrix * vec4(vertexPosition, 1.0);
                    vTextureCoord = aTextureCoord;
                    vVertexPosition = vertexPosition;
                }
            `;

        const VERT_RGBA = `
                precision mediump float;
                attribute vec3 aVertexPosition;
                attribute vec2 aTextureCoord;
                uniform mat4 uMVMatrix;
                uniform mat4 uPMatrix;
                varying vec2 vTextureCoord;
                void main() {
                    gl_Position = uPMatrix * uMVMatrix * vec4(aVertexPosition, 1.0);
                    vTextureCoord = aTextureCoord;
                }
            `;

        const VERT_RIPPLE = `
                precision mediump float;
                attribute vec3 aVertexPosition;
                attribute vec2 aTextureCoord;
                uniform mat4 uMVMatrix;
                uniform mat4 uPMatrix;
                uniform float uTime;
                uniform vec2 uMousePosition;
                uniform float uRippleStrength;
                varying vec2 vTextureCoord;
                varying vec3 vVertexPosition;
                void main() {
                    vec3 vertexPosition = aVertexPosition;
                    float distanceFromMouse = distance(uMousePosition, vec2(vertexPosition.x, vertexPosition.y));
                    float ripple = sin(uTime / 50.0 - distanceFromMouse * 5.0) * uRippleStrength;
                    vertexPosition.z += ripple * 0.05;
                    vertexPosition.x += ripple * 0.02 * (uMousePosition.x - vertexPosition.x);
                    vertexPosition.y += ripple * 0.02 * (uMousePosition.y - vertexPosition.y);
                    gl_Position = uPMatrix * uMVMatrix * vec4(vertexPosition, 1.0);
                    vTextureCoord = aTextureCoord;
                    vVertexPosition = vertexPosition;
                }
            `;

        const VERT_LIQUID = `
                precision mediump float;  
                attribute vec3 aVertexPosition;
                attribute vec2 aTextureCoord;
                uniform mat4 uMVMatrix;
                uniform mat4 uPMatrix;
                uniform mat4 ripple2TextureMatrix;
                varying vec3 vVertexPosition;
                varying vec2 vDisplacementCoord;
                varying vec2 vTextureCoord;
                uniform float uTime;
                void main() {
                    vec3 vertexPosition = aVertexPosition;
                    gl_Position = uPMatrix * uMVMatrix * vec4(vertexPosition, 1.0);
                    vDisplacementCoord = aTextureCoord;
                    vTextureCoord = (ripple2TextureMatrix * vec4(aTextureCoord, 0.0, 1.0)).xy;
                    vVertexPosition = vertexPosition;
                }
            `;

        const VERT_SCROLLWAVE = `
                precision mediump float;
                attribute vec3 aVertexPosition;
                attribute vec2 aTextureCoord;
                uniform mat4 uMVMatrix;
                uniform mat4 uPMatrix;
                uniform float uTime;
                uniform float uScrollSpeed;
                uniform float uScrollAcc;
                varying vec2 vTextureCoord;
                void main() {
                    vec3 vertexPosition = aVertexPosition;
                    gl_Position = uPMatrix * uMVMatrix * vec4(vertexPosition, 1.0);
                    vTextureCoord = aTextureCoord;
                }
            `;

        const VERT_RAIN = `#ifdef GL_ES
                precision mediump float;
                #endif
                
                // those are the mandatory attributes that the lib sets
                attribute vec3 aVertexPosition;
                attribute vec2 aTextureCoord;

                // those are mandatory uniforms that the lib sets and that contain our model view and projection matrix
                uniform mat4 uMVMatrix;
                uniform mat4 uPMatrix;

                uniform mat4 dispImageMatrix;

                // if you want to pass your vertex and texture coords to the fragment shader
                varying vec3 vVertexPosition;
                varying vec2 vTextureCoord;

                void main() {
                    vec3 vertexPosition = aVertexPosition;
                    gl_Position = uPMatrix * uMVMatrix * vec4(vertexPosition, 1.0);

                    // set the varyings
                    vTextureCoord = (dispImageMatrix * vec4(aTextureCoord, 0., 1.)).xy;
                    vVertexPosition = vertexPosition;
                }
            `;

        const FRAG_DEFAULT = `
                precision mediump float;
                varying vec2 vTextureCoord;
                uniform sampler2D uTexture;
                uniform float uAlpha;
                void main() {
                    vec4 c = texture2D(uTexture, vTextureCoord);
                    gl_FragColor = vec4(c.rgb, c.a * uAlpha);
                }
            `;

        const FRAG_WAVE3D = `
                precision mediump float;
                varying vec3 vVertexPosition;
                varying vec2 vTextureCoord;
                uniform sampler2D uTexture;
                uniform float uAlpha;
                void main() {
                    vec4 finalColor = texture2D(uTexture, vTextureCoord);
                    finalColor.rgb -= clamp(-vVertexPosition.z, 0.0, 1.0);
                    finalColor.rgb += clamp(vVertexPosition.z, 0.0, 1.0);
                    finalColor = vec4(finalColor.rgb * finalColor.a, finalColor.a);
                    gl_FragColor = vec4(finalColor.rgb, finalColor.a * uAlpha);
                }
            `;

        const FRAG_RGBA = `
                precision highp float;
                varying vec2 vTextureCoord;
                uniform sampler2D uTexture;
                uniform float uTime;
                uniform float uStrength;
                uniform vec2 uMouse;
                uniform bool uHover;
                uniform float uAlpha;
                void main() {
                    vec2 uv = vTextureCoord;
                    vec4 color;
                    if (uHover) {
                        vec2 mouseDir = uv - uMouse;
                        float dist = length(mouseDir);
                        float angle = atan(mouseDir.y, mouseDir.x);
                        float wave = sin(dist * 20.0 - angle) * 0.02;
                        float effect = uStrength * smoothstep(0.45, 0.1, dist);
                        vec2 offset = normalize(mouseDir) * wave * effect;
                        vec2 distortedUV = uv + offset;
                        vec2 rgbDir = normalize(mouseDir);
                        float rgbOffset = 0.02 * effect;
                        float r = texture2D(uTexture, distortedUV + rgbDir * rgbOffset).r;
                        float g = texture2D(uTexture, distortedUV).g;
                        float b = texture2D(uTexture, distortedUV - rgbDir * rgbOffset).b;
                        color = vec4(r, g, b, 1.0);
                    } else {
                        color = texture2D(uTexture, uv);
                    }
                    gl_FragColor = vec4(color.rgb, color.a * uAlpha);
                }
            `;

        const FRAG_RIPPLE = `
                precision mediump float;
                varying vec3 vVertexPosition;
                varying vec2 vTextureCoord;
                uniform sampler2D uTexture;
                uniform float uTime;
                uniform float uRippleStrength;
                uniform float uAlpha;
                void main() {
                    vec4 finalColor = texture2D(uTexture, vTextureCoord);
                    finalColor.rgb -= clamp(-vVertexPosition.z, 0.0, 1.0) * 0.5;
                    finalColor.rgb += clamp(vVertexPosition.z, 0.0, 1.0) * 0.5;
                    finalColor = vec4(finalColor.rgb * finalColor.a, finalColor.a);
                    gl_FragColor = vec4(finalColor.rgb, finalColor.a * uAlpha);
                }
            `;

        const FRAG_LIQUID = `
                precision mediump float;
                varying vec3 vVertexPosition;
                varying vec2 vDisplacementCoord;
                varying vec2 vTextureCoord;
                uniform sampler2D ripple2Texture;
                uniform sampler2D ripple2Displacement;
                uniform float uTime;            
                uniform float effectIntensity;   
                uniform float liquidStrength;      
                uniform float liquidFlow;
                uniform float uAlpha;  
                void main(void) {
                    vec2 adjustedCoords = vDisplacementCoord;
                    float distanceFromTopCenter = 1.0 - vDisplacementCoord.y + abs(vDisplacementCoord.x - 0.5) * 0.5;
                    float phaseShift = distanceFromTopCenter - uTime / (250.0 + effectIntensity * 5.0);
                    vec2 displacementCoords = vec2(
                        mod(adjustedCoords.x - (1.0 - liquidFlow) * phaseShift * 0.5, 1.0),
                        mod(adjustedCoords.y + liquidFlow * phaseShift, 1.0)
                    );
                    vec4 displacementTexture = texture2D(ripple2Displacement, displacementCoords);
                    vec2 textureCoords = vTextureCoord;
                    textureCoords.x -= liquidStrength * (displacementTexture.r - 0.5) * 0.012 * (1.0 + liquidFlow);
                    textureCoords.y += liquidStrength * (displacementTexture.r - 0.5) * 0.012 * (1.0 - liquidFlow);
                    vec4 finalColor = texture2D(ripple2Texture, textureCoords);
                    finalColor = vec4(finalColor.rgb * finalColor.a, finalColor.a);
                    gl_FragColor = vec4(finalColor.rgb, finalColor.a * uAlpha);
                }
            `;

        const FRAG_SCROLLWAVE = `
                precision highp float;
                varying vec2 vTextureCoord;
                uniform sampler2D uTexture;
                uniform float uTime;
                uniform float uScrollSpeed;
                uniform float uScrollAcc;
                uniform vec2 uResolution;
                uniform float uId;
                uniform float uAlpha;

                vec4 readTex(vec2 uv) {
                    vec4 color = texture2D(uTexture, uv);
                    // Apply smooth edge fading from corners only to alpha
                    float edgeFade = smoothstep(0.5, 0.499, abs(uv.x - 0.5)) * smoothstep(0.5, 0.499, abs(uv.y - 0.5));
                    color.a *= edgeFade;
                    return color;
                }

                void main() {
                    vec2 uv = vTextureCoord;
                    float noiseScale = (sin(uId) * 0.3 + 0.6) * uScrollSpeed;

                    // Wave distortions with scroll influence
                    uv.y += sin((uv.x - uv.y * 0.11 + uId) * 9.0 + uTime * 0.1 + uScrollAcc * 0.03 + uId * 3.0) * 0.1 * noiseScale;
                    uv.x += sin(uv.y * 11.0 + uScrollAcc * 0.02 + uId * 7.0 + uTime * 0.08) * 0.03 * noiseScale;
                    uv.y += sin((uv.x + uv.y * 0.13 + uId * 3.0 + uTime * sin(uId) * 0.03) * 2.0 + sin(uId * 11.0) * 0.2 - uScrollAcc * 0.03) * 0.08 * noiseScale;
                    uv.y += sin((uv.x - uv.y * 0.17 + uId) * 23.0 - uTime * 0.004 + uId) * 0.03 * noiseScale;

                    vec4 color = readTex(uv);
                    // Apply time-based fade-in to alpha only, preserving original RGB
                    color.a *= smoothstep(0.0, 2.0, uTime);
                    gl_FragColor = vec4(color.rgb, color.a * uAlpha);
                }
            `;

        const VERT_ABERRATION = `
                precision mediump float;

                // default mandatory variables
                attribute vec3 aVertexPosition;
                attribute vec2 aTextureCoord;

                uniform mat4 uMVMatrix;
                uniform mat4 uPMatrix;

                uniform mat4 planeTextureMatrix;

                // custom variables
                varying vec3 vVertexPosition;
                varying vec2 vTextureCoord;

                uniform vec2 uMousePosition;
                uniform float uTime;
                uniform float uTransition;
                uniform float uAspect;
                uniform vec2 uResolution;

                void main() {
                    vec3 vertexPosition = aVertexPosition;

                    // convert uTransition from [0,1] to [0,1,0]
                    float transition = 1.0 - abs((uTransition * 2.0) - 1.0);

                    //vertexPosition.x *= (1. + transition * 2.25);

                    // get the distance between our vertex and the mouse position
                    float distanceFromMouse = distance(uMousePosition, vec2(vertexPosition.x, vertexPosition.y));

                    // calculate our wave effect
                    float waveSinusoid = cos(5.0 * (distanceFromMouse - (uTime / 30.0)));

                    // attenuate the effect based on mouse distance
                    float distanceStrength = (0.4 / (distanceFromMouse + 0.4));

                    // calculate our distortion effect
                    float distortionEffect = distanceStrength * waveSinusoid * 0.33;

                    // apply it to our vertex position
                    vertexPosition.z +=  distortionEffect * -transition;
                    vertexPosition.x +=  (distortionEffect * transition * (uMousePosition.x - vertexPosition.x));
                    vertexPosition.y +=  distortionEffect * transition * (uMousePosition.y - vertexPosition.y);

                    gl_Position = uPMatrix * uMVMatrix * vec4(vertexPosition, 1.0);

                    // varyings
                    vVertexPosition = vertexPosition;
                    vTextureCoord = (planeTextureMatrix * vec4(aTextureCoord, 0.0, 1.0)).xy;
                }
            `;

        const FRAG_RAIN = `
                #ifdef GL_ES
                precision mediump float;
                #endif

                #define PI2 6.28318530718
                #define PI 3.14159265359
                #define S(a,b,n) smoothstep(a,b,n)

                varying vec3 vVertexPosition;
                varying vec2 vTextureCoord;

                uniform float uTime;
                uniform vec2 uResolution;
                uniform vec2 uMouse;
                uniform sampler2D dispImage;
                uniform sampler2D blurImage;

                // uHover is now float (0.0 to 1.0) for smooth GSAP animation
                uniform float uHover;
                uniform float uAlpha;

                // Noise function
                float N12(vec2 p) {
                    p = fract(p * vec2(123.34, 345.45));
                    p += dot(p, p + 34.345);
                    return fract(p.x * p.y);
                }

                // Rain drop layer
                vec3 Layer(vec2 uv0, float t) {
                    vec2 asp = vec2(2.0, 1.0);
                    vec2 uv1 = uv0 * 3.0 * asp;
                    uv1.y += t * 0.25;
                    vec2 gv = fract(uv1) - 0.5;
                    vec2 id = floor(uv1);
                    float n = N12(id);
                    t += n * PI2;

                    float w = uv0.y * 10.0;
                    float x = (n - 0.5) * 0.8;
                    x += (0.4 - abs(x)) * sin(3.0 * w) * pow(sin(w), 6.0) * 0.45;
                    float y = -sin(t + sin(t + sin(t) * 0.5)) * (0.5 - 0.06);
                    y -= (gv.x - x) * (gv.x - x);

                    vec2 dropPos = (gv - vec2(x, y)) / asp;
                    float drop = S(0.03, 0.02, length(dropPos));

                    vec2 trailPos = (gv - vec2(x, t * 0.25)) / asp;
                    trailPos.y = (fract(trailPos.y * 8.0) - 0.5) / 8.0;
                    float trail = S(0.02, 0.015, length(trailPos));

                    float fogTrail = S(-0.05, 0.05, dropPos.y);
                    fogTrail *= S(0.5, y, gv.y);
                    trail *= fogTrail;
                    fogTrail *= S(0.03, 0.015, abs(dropPos.x));

                    vec2 off = drop * dropPos + trail * trailPos;
                    return vec3(off, fogTrail);
                }

                void main() {
                    vec2 uv = vTextureCoord;
                    vec4 original = texture2D(dispImage, uv);

                    // Early exit if not hovering — show clean image
                    if (uHover < 0.01) {
                        gl_FragColor = vec4(original.rgb, original.a * uAlpha);
                        return;
                    }

                    // === RAIN + BLUR EFFECT (only runs when uHover > 0) ===
                    float t = mod(uTime * 0.03, 7200.0);
                    vec4 col = vec4(0.0);

                    // Multiple rain layers
                    vec3 drops = Layer(uv, t);
                    drops += Layer(uv * 1.25 + 7.54, t);
                    drops += Layer(uv * 1.35 + 1.54, t);
                    drops += Layer(uv * 1.57 - 7.54, t);

                    float blur = 5.0 * 7.0 * (1.0 - drops.z);
                    blur *= 0.0005;
                    uv += drops.xy * 5.0;

                    int numSamples = 32;
                    float a = N12(uv) * PI2;

                    for (int n = 0; n < 32; n++) {
                        vec2 off = vec2(sin(a), cos(a)) * blur;
                        float d = fract(sin(float(n + 1) * 546.0) * 5424.0);
                        d = sqrt(d);
                        off *= d;
                        col += texture2D(dispImage, uv + off);
                        a += 1.0;
                    }
                    col /= float(numSamples);

                    // === SMOOTH FADE: original to rain effect ===
                    vec4 final = mix(original, col, uHover);
                    gl_FragColor = vec4(final.rgb, final.a * uAlpha);
                }
            `;

        const FRAG_ABERRATION = `
                precision mediump float;

                varying vec3 vVertexPosition;
                varying vec2 vTextureCoord;

                uniform sampler2D planeTexture;
                uniform float uAlpha;
                uniform float uAspect;

                void main( void ) {
                    // apply our texture
                    vec4 finalColor = texture2D(planeTexture, vTextureCoord);

                    // fake shadows based on vertex position along Z axis
                    finalColor.rgb += clamp(vVertexPosition.z, -1.0, 0.0) * 0.75;
                    // fake lights based on vertex position along Z axis
                    finalColor.rgb += clamp(vVertexPosition.z, 0.0, 1.0) * 0.75;

                    // just display our texture
                   gl_FragColor = vec4(finalColor.rgb, finalColor.a * uAlpha);
                }
                `;

        const DISPLACEMENT_VERT_ABERRATION = `
                #ifdef GL_FRAGMENT_PRECISION_HIGH
                precision highp float;
                #else
                precision mediump float;
                #endif

                // default mandatory variables
                attribute vec3 aVertexPosition;
                attribute vec2 aTextureCoord;

                // custom variables
                varying vec3 vVertexPosition;
                varying vec2 vTextureCoord;

                void main() {
                    gl_Position = vec4(aVertexPosition, 1.0);

                    // set the varyings
                    vTextureCoord = aTextureCoord;
                    vVertexPosition = aVertexPosition;
                }
            `;

        const DISPLACEMENT_FRAG_ABERRATION = `
                #ifdef GL_FRAGMENT_PRECISION_HIGH
                precision highp float;
                #else
                precision mediump float;
                #endif

                // get our varyings
                varying vec3 vVertexPosition;
                varying vec2 vTextureCoord;

                // our render texture
                uniform sampler2D uRenderTexture;
                uniform sampler2D uFlowTexture;

                void main() {
                    // our flowmap
                    vec4 flowTexture = texture2D(uFlowTexture, vTextureCoord);

                    // distort our image texture based on the flowmap values
                    vec2 distortedCoords = vTextureCoord;
                    distortedCoords -= flowTexture.xy * 0.15;

                    // get our final texture based on the displaced coords
                    vec4 texture = texture2D(uRenderTexture, distortedCoords);

                    vec4 rTexture = texture2D(uRenderTexture, distortedCoords + flowTexture.xy * 0.025);
                    vec4 gTexture = texture2D(uRenderTexture, distortedCoords);
                    vec4 bTexture = texture2D(uRenderTexture, distortedCoords - flowTexture.xy * 0.025);

                    // mix the BW image and the colored one based on our flowmap color values
                    float mixValue = clamp((abs(flowTexture.r) + abs(flowTexture.g) + abs(flowTexture.b)) * 3.5, 0.0, 1.0);

                    texture = mix(texture, vec4(rTexture.r, gTexture.g, bTexture.b, texture.a), mixValue);

                    gl_FragColor = texture;
                }
            `;

        const FLOWMAP_VERT_ABERRATION = `
                // #ifdef GL_FRAGMENT_PRECISION_HIGH
                precision highp float;
                // #else
                // precision mediump float;
                // #endif

                // default mandatory variables
                attribute vec3 aVertexPosition;
                attribute vec2 aTextureCoord;

                uniform mat4 uMVMatrix;
                uniform mat4 uPMatrix;

                // custom variables
                varying vec3 vVertexPosition;
                varying vec2 vTextureCoord;

                void main() {
                    vec3 vertexPosition = aVertexPosition;

                    gl_Position = uPMatrix * uMVMatrix * vec4(vertexPosition, 1.0);

                    // varyings
                    vTextureCoord = aTextureCoord;
                    vVertexPosition = vertexPosition;
                }
            `;

        const FLOWMAP_FRAG_ABERRATION = `
                #ifdef GL_FRAGMENT_PRECISION_HIGH
                precision highp float;
                #else
                precision mediump float;
                #endif

                varying vec3 vVertexPosition;
                varying vec2 vTextureCoord;

                uniform sampler2D uFlowMap;

                uniform vec2 uMousePosition;
                uniform float uFalloff;
                uniform float uAlpha;
                uniform float uDissipation;
                uniform float uScrollDiff;
                uniform vec2 uVelocity;
                uniform float uAspect;
                uniform float uScrollDirection;

                void main() {
                    vec2 textCoords = vTextureCoord;
                    vec2 mousePos = uMousePosition;
                    float falloffAdjust = 0.0;
                    vec2 velocity = uVelocity;

                    vec4 color = texture2D(uFlowMap, textCoords) * uDissipation;
                    // vec4 color = vec4(0.0, 0.0, 0.0, 1.0) * uDissipation;

                    if(abs(uScrollDiff) > 0.1) {
                    // set position based in center on scroll
                    float scrollDirection = uScrollDirection * 0.5;
                    mousePos = vec2(0.1, scrollDirection);
                    falloffAdjust += abs(uScrollDiff) * 0.01 / 2.;
                    }

                    vec2 mouseTexPos = (mousePos + 1.0) * 0.5;
                    vec2 cursor = vTextureCoord - mouseTexPos;

                    vec3 stamp = vec3(velocity * vec2(1.0, -1.0), 1.0 - pow(1.0 - min(1.0, length(velocity)), 3.0));
                    stamp+=uScrollDiff*0.2;
                    float falloff = smoothstep(uFalloff + falloffAdjust, 0.0, length(cursor)) * uAlpha;
                    color.rgb = mix(color.rgb, stamp, vec3(falloff));
                    gl_FragColor = color;
                }
            `;

        const VERT_SHADERS = {
            simple: VERT_DEFAULT,
            wave3d: VERT_WAVE3D,
            rgba: VERT_RGBA,
            ripple: VERT_RIPPLE,
            liquid: VERT_LIQUID,
            scrollWave: VERT_SCROLLWAVE,
            aberration: VERT_ABERRATION,
            rain: VERT_RAIN,
        };
        const FRAG_SHADERS = {
            simple: FRAG_DEFAULT,
            wave3d: FRAG_WAVE3D,
            rgba: FRAG_RGBA,
            ripple: FRAG_RIPPLE,
            liquid: FRAG_LIQUID,
            scrollWave: FRAG_SCROLLWAVE,
            aberration: FRAG_ABERRATION,
            rain: FRAG_RAIN,
        };

        const selector = '.wgl-webgl-plane_wrapper';

        let scrollSpeed = 0;
        let scrollAcc = 0;
        let lastScrollY = 0;

        function initScrollHandler(plane, index) {
            $(window).on('scroll', () => {
                const scrollY = window.scrollY;
                const diff = scrollY - lastScrollY;
                scrollSpeed = lerp(
                    scrollSpeed,
                    Math.abs(diff),
                    0.08 + index * 0.004
                );
                scrollAcc += scrollSpeed * 0.025;
                lastScrollY = scrollY;
                plane.uniforms.uScrollSpeed.value = scrollSpeed * 0.08;
                plane.uniforms.uScrollAcc.value = scrollAcc;
            });
        }

        function lerp(a, b, t) {
            return a * (1 - t) + b * t;
        }

        $context.find(selector).each(function (i, el) {
            const $el = $(el);
            if ($el.data('wgl-webgl-initialized')) return;

            if (window.wglCurtainsMobile) {
                $el.data('wgl-webgl-initialized', true);
                return;
            }
            $el.data('wgl-webgl-initialized', true);

            const $img = $el.find('img').first();
            if (!$img.length) return;
            const imgSrc = $img.attr('src');
            if (!imgSrc) return;

            const effect = $el.data('image-effect') || 'simple';

            if (effect === 'flowmap') {
                if ($('#wgl-flowmap-canvas').length) return;
                initFullscreenFlowmap();
                return;
            }

            if (effect === 'waterRipples') {
                initWaterRipples($el);
                return;
            }

            const VERTEX = VERT_SHADERS[effect] || VERT_DEFAULT;
            const FRAG = FRAG_SHADERS[effect] || FRAG_DEFAULT;

            $img.wrap('<div class="wgl-webgl-plane"></div>');
            const $planeWrap = $img.parent();
            const containerId =
                'wgl-webgl-' + i + '-' + Math.floor(Math.random() * 9999);
            $el.attr('id', containerId);

            const premultiplied = $el.data('premultiplied');
            
            const optionsCurtains = {
                container: document.getElementById(containerId),
                watchScroll: false,
                pixelRatio: Math.min(1.5, window.devicePixelRatio),
                autoRender: true,
            };

            if (premultiplied === 'yes') {
                optionsCurtains.premultipliedAlpha = true;
                optionsCurtains.transparent = true;
            }

            const curtains = new Curtains(optionsCurtains);

            curtains.onContextLost(() => {
                jQuery('#' + containerId).addClass('context-missed');
            });

            window.wglCurtains.push({
                container: el,
                instance: curtains,
                itemID: containerId,
            });

            const mousePosition = new Vec2();
            const mouseLastPosition = new Vec2();
            const lastMouse = mousePosition.clone();
            const velocity = new Vec2();
            const deltas = { max: 0, applied: 0 };
            const dispURL = createNoiseDataURL(256);
            let updateVelocity = false;

            let texturesOptions = [
                { sampler: 'uTexture', fromTexture: imgSrc },
            ];

            if (effect === 'liquid') {
                $img.attr({
                    crossorigin: '',
                    'data-sampler': 'ripple2Texture',
                });
                const displacementImg = document.createElement('img');
                displacementImg.setAttribute('crossorigin', '');
                displacementImg.setAttribute(
                    'src',
                    WGLHoverVars.img + '/cloudnoise_1.webp'
                );
                displacementImg.setAttribute(
                    'data-sampler',
                    'ripple2Displacement'
                );
                displacementImg.setAttribute('class', 'displacement');
                $planeWrap[0].appendChild(displacementImg);
                texturesOptions.push({
                    sampler: 'ripple2Displacement',
                    fromTexture: WGLHoverVars.img + '/cloudnoise_1.webp',
                });
            } else if (effect === 'aberration') {
                $img.attr({
                    'data-sampler': 'planeTexture',
                });
            } else if (effect === 'rain') {
                $img.attr({ 'data-sampler': 'dispImage' });
            } else {
                texturesOptions.push({
                    sampler: 'uDisp',
                    fromTexture: dispURL,
                });
            }
            const bbox = curtains.getBoundingRect();

            const params = {
                vertexShader: VERTEX,
                fragmentShader: FRAG,
                widthSegments: 20,
                heightSegments: 20,
                uniforms: {
                    uTime: { name: 'uTime', type: '1f', value: 0 },
                    uResolution: {
                        name: 'uResolution',
                        type: '2f',
                        value: [$el.width(), $el.height()],
                    },
                    uMousePosition: {
                        name: 'uMousePosition',
                        type: '2f',
                        value: mousePosition,
                    },
                    uMouseMoveStrength: {
                        name: 'uMouseMoveStrength',
                        type: '1f',
                        value: 0,
                    },
                    uMouse: { name: 'uMouse', type: '2f', value: [0.5, 0.5] },
                    uStrength: { name: 'uStrength', type: '1f', value: 0 },
                    uHover: { name: 'uHover', type: '1f', value: 0 },
                    uRippleStrength: {
                        name: 'uRippleStrength',
                        type: '1f',
                        value: 0,
                    },
                    effectIntensity: {
                        name: 'effectIntensity',
                        type: '1f',
                        value: 40.0,
                    },
                    liquidStrength: {
                        name: 'liquidStrength',
                        type: '1f',
                        value: 0,
                    },
                    uScrollSpeed: {
                        name: 'uScrollSpeed',
                        type: '1f',
                        value: 0,
                    },
                    uScrollAcc: { name: 'uScrollAcc', type: '1f', value: 0 },
                    uId: { name: 'uId', type: '1f', value: i },
                    aspect: {
                        name: 'uAspect',
                        type: '1f',
                        value: bbox.width / bbox.height,
                    },
                    uAlpha: { name: 'uAlpha', type: '1f', value: 0 }
                },
                texturesOptions: texturesOptions,
            };

            const plane = new Plane(curtains, $planeWrap[0], params);

            plane.onReady(() => {
                if (effect === 'wave3d') {
                    plane.setPerspective(35);
                    const wrapper = $el[0];
                    wrapper.addEventListener('mousemove', (e) =>
                        handleMovement(e, plane)
                    );
                    wrapper.addEventListener(
                        'touchmove',
                        (e) => handleMovement(e, plane),
                        { passive: true }
                    );
                } else if (effect === 'rgba') {
                    const wrapper = $el[0];
                    let isOver = false;
                    window.addEventListener("mousemove", (e) => {
                        const r = wrapper.getBoundingClientRect();

                        const inside =
                            e.clientX >= r.left &&
                            e.clientX <= r.right &&
                            e.clientY >= r.top &&
                            e.clientY <= r.bottom;

                        if (inside) {
                            if (!isOver) {
                                plane.uniforms.uHover.value = 1;
                                gsap.to(plane.uniforms.uHover, {
                                    value: 1,
                                    duration: 0.4,
                                    ease: 'power4.out',
                                });
                                isOver = true;
                            }
                            handleHoverMovement(e, plane);
                        } else {
                            if (isOver) {
                                gsap.to(plane.uniforms.uHover, {
                                    value: 0,
                                    duration: 0.4,
                                    ease: 'power4.out',
                                });
                                gsap.to(plane.uniforms.uStrength, {
                                    value: 0,
                                    duration: 0.4,
                                    ease: 'power4.out',
                                });
                                isOver = false;
                            }
                        }
                    });
                } else if (effect === 'ripple') {
                    plane.setPerspective(35);
                    const wrapper = $el[0];
                    const trigger = $el.data('trigger') || 'hover';
                    if ('visible' === trigger) {
                        const playAnimation = () => {
                            gsap.to(plane.uniforms.uRippleStrength, {
                                value: 1,
                                duration: 0.8,
                                ease: 'power4.out',
                            });
                        };

                        const stopAnimation = () => {
                            gsap.to(plane.uniforms.uRippleStrength, {
                                value: 0,
                                duration: 0.6,
                                ease: 'power4.out',
                            });
                        };

                        const observer = new IntersectionObserver(
                            (entries) => {
                                entries.forEach((entry) => {
                                    if (entry.isIntersecting) {
                                        playAnimation();
                                    } else {
                                        stopAnimation();
                                    }
                                });
                            },
                            {
                                threshold: 0,
                            }
                        );

                        observer.observe(wrapper);
                    } else {
                        let isOver = false;

                        window.addEventListener("mousemove", (e) => {
                            const r = wrapper.getBoundingClientRect();

                            const inside =
                                e.clientX >= r.left &&
                                e.clientX <= r.right &&
                                e.clientY >= r.top &&
                                e.clientY <= r.bottom;

                            if (inside && !isOver) {
                                isOver = true;

                                gsap.to(plane.uniforms.uRippleStrength, {
                                    value: 1,
                                    duration: 0.5,
                                    ease: "power4.out",
                                });
                            }

                            if (!inside && isOver) {
                                isOver = false;

                                gsap.to(plane.uniforms.uRippleStrength, {
                                    value: 0,
                                    duration: 0.5,
                                    ease: "power4.out",
                                });
                            }
                        });
                    }
                } else if (effect === 'liquid') {
                    const wrapper = $el[0];
                    let isOver = false;
                    window.addEventListener("mousemove", (e) => {
                        const r = wrapper.getBoundingClientRect();

                        const inside =
                            e.clientX >= r.left &&
                            e.clientX <= r.right &&
                            e.clientY >= r.top &&
                            e.clientY <= r.bottom;

                        if (inside && !isOver) {
                            isOver = true;

                            gsap.to(plane.uniforms.liquidStrength, {
                                value: 1,
                                duration: 0.5,
                                ease: "power4.out",
                            });
                        }

                        if (inside) {
                            handleLiquidMovement(e, plane);
                        }

                        if (!inside && isOver) {
                            isOver = false;

                            gsap.to(plane.uniforms.liquidStrength, {
                                value: 0,
                                duration: 0.5,
                                ease: "power4.out",
                            });
                        }
                    });
                } else if (effect === 'scrollWave') {
                    initScrollHandler(plane, i);
                } else if (effect === 'aberration') {
                    const wrapper = $el[0];
                    wrapper.addEventListener('mousemove', (e) =>
                        handleAberrationMovement(e, plane)
                    );
                } else if (effect === 'rain') {
                    const wrapper = $el[0];
                    let isHovering = false;
                    let rainTime = 0;

                    window.addEventListener("mousemove", (e) => {
                        const r = wrapper.getBoundingClientRect();

                        const inside =
                            e.clientX >= r.left &&
                            e.clientX <= r.right &&
                            e.clientY >= r.top &&
                            e.clientY <= r.bottom;

                        if (inside && !isHovering) {
                            isHovering = true;

                            gsap.to(plane.uniforms.uHover, {
                                value: 1,
                                duration: 0.4,
                            });
                        }

                        if (!inside && isHovering) {
                            isHovering = false;

                            gsap.to(plane.uniforms.uHover, {
                                value: 0,
                                duration: 0.6,
                            });
                        }
                    });

                    plane.userData.isRainHovering = () => isHovering;
                    plane.userData.rainTime = () => rainTime++;
                }
                gsap.to(plane.uniforms.uAlpha, {
                    value: 1,
                    duration: 1.6,
                    ease: "power3.out",
                    delay: i * 0.07
                });
            });

            plane.onRender(() => {
                plane.uniforms.uTime.value++;
                if (effect === 'wave3d') {
                    deltas.applied += (deltas.max - deltas.applied) * 0.02;
                    deltas.max += (0 - deltas.max) * 0.01;
                    plane.uniforms.uMouseMoveStrength.value = deltas.applied;
                } else if (effect === 'rgba') {
                    const deltaTime = 1 / 60;
                    const targetStrength = plane.uniforms.uHover.value ? 1 : 0;
                    plane.uniforms.uStrength.value +=
                        (targetStrength - plane.uniforms.uStrength.value) *
                        deltaTime *
                        4;
                    plane.uniforms.uStrength.value = Math.max(
                        0,
                        Math.min(1, plane.uniforms.uStrength.value)
                    );
                } else if (effect === 'ripple') {
                    const deltaTime = 1 / 60;
                    const targetStrength = plane.uniforms.uRippleStrength.value;
                    plane.uniforms.uRippleStrength.value +=
                        (targetStrength -
                            plane.uniforms.uRippleStrength.value) *
                        deltaTime *
                        4;
                    plane.uniforms.uRippleStrength.value = Math.max(
                        0,
                        Math.min(1, plane.uniforms.uRippleStrength.value)
                    );
                } else if (effect === 'liquid') {
                    const deltaTime = 1 / 60;
                    const targetForce = plane.uniforms.liquidStrength.value;
                    plane.uniforms.liquidStrength.value +=
                        (targetForce - plane.uniforms.liquidStrength.value) *
                        deltaTime *
                        4;
                    plane.uniforms.liquidStrength.value = Math.max(
                        0,
                        Math.min(1, plane.uniforms.liquidStrength.value)
                    );
                } else if (effect === 'scrollWave') {
                    plane.uniforms.uScrollSpeed.value *= 0.95 + 0.002 * i;
                } else if (effect === 'rain') {
                    const rect = plane.getBoundingRect();
                    plane.uniforms.uResolution.value = [
                        rect.width,
                        rect.height,
                    ];

                    if (plane.uniforms.uHover.value > 0.01) {
                        plane.uniforms.uTime.value += 1;
                    }
                }
            });

            plane.onAfterResize(() => {
                const rect = plane.getBoundingRect();
                plane.uniforms.uResolution.value = [rect.width, rect.height];
            });

            function handleMovement(e, plane) {
                mouseLastPosition.copy(mousePosition);
                const mouse = new Vec2();
                if (e.targetTouches)
                    mouse.set(
                        e.targetTouches[0].clientX,
                        e.targetTouches[0].clientY
                    );
                else mouse.set(e.clientX, e.clientY);
                mousePosition.set(
                    curtains.lerp(mousePosition.x, mouse.x, 0.3),
                    curtains.lerp(mousePosition.y, mouse.y, 0.3)
                );

                plane.uniforms.uMousePosition.value =
                    plane.mouseToPlaneCoords(mousePosition);

                if (mouseLastPosition.x && mouseLastPosition.y) {
                    let delta =
                        Math.sqrt(
                            Math.pow(mousePosition.x - mouseLastPosition.x, 2) +
                                Math.pow(
                                    mousePosition.y - mouseLastPosition.y,
                                    2
                                )
                        ) / 30;
                    delta = Math.min(4, delta);
                    if (delta >= deltas.max) deltas.max = delta;
                }
            }

            function handleHoverMovement(e, plane) {
                const rect = $planeWrap[0].getBoundingClientRect();
                const x = (e.clientX - rect.left) / rect.width;
                const y = 1.0 - (e.clientY - rect.top) / rect.height;
                plane.uniforms.uMouse.value = [x, y];
            }

            function handleLiquidMovement(e, plane) {
                const mouse = new Vec2();
                if (e.targetTouches)
                    mouse.set(
                        e.targetTouches[0].clientX,
                        e.targetTouches[0].clientY
                    );
                else mouse.set(e.clientX, e.clientY);
                mousePosition.set(
                    curtains.lerp(mousePosition.x, mouse.x, 0.3),
                    curtains.lerp(mousePosition.y, mouse.y, 0.3)
                );
                plane.uniforms.uMousePosition.value =
                    plane.mouseToPlaneCoords(mousePosition);
            }

            function handleAberrationMovement(e, plane) {
                lastMouse.copy(mousePosition);
                if (e.targetTouches) {
                    mousePosition.set(
                        e.targetTouches[0].clientX,
                        e.targetTouches[0].clientY
                    );
                } else {
                    mousePosition.set(e.offsetX, e.offsetY);
                }

                velocity.set(
                    (mousePosition.x - lastMouse.x) * 0.1,
                    (mousePosition.y - lastMouse.y) * 0.1
                );

                updateVelocity = true;
            }

            if (effect === 'aberration') {
                let flowMap = null;

                const flowMapParams = {
                    sampler: 'uFlowMap',
                    vertexShader: FLOWMAP_VERT_ABERRATION,
                    fragmentShader: FLOWMAP_FRAG_ABERRATION,
                    watchScroll: false,
                    texturesOptions: {
                        floatingPoint: 'half-float',
                    },
                    uniforms: {
                        uMousePosition: {
                            name: 'uMousePosition',
                            type: '2f',
                            value: mousePosition,
                        },
                        fallOff: {
                            name: 'uFalloff',
                            type: '1f',
                            value:
                                bbox.width > bbox.height
                                    ? bbox.width / 1000
                                    : bbox.height / 1000,
                        },
                        alpha: {
                            name: 'uAlpha',
                            type: '1f',
                            value: 1,
                        },
                        dissipation: {
                            name: 'uDissipation',
                            type: '1f',
                            value: 0.05,
                        },
                        velocity: {
                            name: 'uVelocity',
                            type: '2f',
                            value: velocity,
                        },
                        aspect: {
                            name: 'uAspect',
                            type: '1f',
                            value: bbox.width / bbox.height,
                        },
                        scrollDiff: {
                            name: 'uScrollDiff',
                            type: '1f',
                            value: 0,
                        },
                        scrollDirection: {
                            name: 'uScrollDirection',
                            type: '1f',
                            value: 0,
                        },
                        uResolution: {
                            name: 'uResolution',
                            type: '1f',
                            value: {
                                x: window.innerWidth,
                                y: window.innerHeight,
                            },
                        },
                    },
                };

                flowMap = new PingPongPlane(
                    curtains,
                    curtains.container,
                    flowMapParams
                );

                function mouseToPlaneCoords(mouse, canvasWidth, canvasHeight) {
                    const xClipSpace = (mouse.x / canvasWidth) * 2 - 1;
                    const yClipSpace = 1 - (mouse.y / canvasHeight) * 2;

                    return new Vec2(xClipSpace, yClipSpace);
                }

                flowMap
                    .onRender(() => {
                        const mouseUv = mouseToPlaneCoords(
                            mousePosition,
                            bbox.width,
                            bbox.height
                        );

                        flowMap.uniforms.uMousePosition.value = mouseUv;

                        if (!updateVelocity) {
                            velocity.set(
                                curtains.lerp(velocity.x, 0, 0.1),
                                curtains.lerp(velocity.y, 0, 0.1)
                            );
                        }
                        updateVelocity = false;

                        flowMap.uniforms.velocity.value = new Vec2(
                            curtains.lerp(velocity.x, 0, 0.1),
                            curtains.lerp(velocity.y, 0, 0.1)
                        );
                    })
                    .onAfterResize(() => {
                        const boundingRect = flowMap.getBoundingRect();
                        flowMap.uniforms.aspect.value =
                            boundingRect.width / boundingRect.height;
                        flowMap.uniforms.fallOff.value =
                            bbox.width > bbox.height
                                ? bbox.width / 1000
                                : bbox.height / 1000;
                    });

                const passParams = {
                    vertexShader: DISPLACEMENT_VERT_ABERRATION,
                    fragmentShader: DISPLACEMENT_FRAG_ABERRATION,
                    depth: false,
                };

                const shaderPass = new ShaderPass(curtains, passParams);

                const flowTexture = shaderPass.createTexture({
                    sampler: 'uFlowTexture',
                    floatingPoint: 'half-float',
                    fromTexture: flowMap.getTexture(),
                });

                flowTexture.onSourceUploaded(() => {
                    const fxaaPass = new FXAAPass(curtains);
                });
            }
        });

        function initWaterRipples(el) {
            if (typeof jQuery.fn.ripples === 'function') {
                const $wrapper = el;
                const $img = $wrapper.find('img');

                if ($img.length) {
                    const imgSrc = $img.attr('src');
                    $wrapper.css({
                        'background-image': 'url(' + imgSrc + ')',
                        'background-size': 'cover',
                        'background-position': 'center center',
                        position: 'relative',
                    });

                    $img.css({ opacity: '0', visibility: 'hidden' });
                }

                $wrapper.ripples({
                    resolution: 512,
                    dropRadius: 20,
                    perturbance: 0.01,
                    interactive: true,
                });

                const attachHandler = (canvas) => {
                    if (!canvas) return;
                    canvas.addEventListener('webglcontextlost', (event) => {
                        $wrapper.addClass('context-missed');
                    });
                };

                const existingCanvas = $wrapper.find('canvas')[0];
                if (existingCanvas) {
                    attachHandler(existingCanvas);
                } else {
                    const observer = new MutationObserver(() => {
                        const newCanvas = $wrapper.find('canvas')[0];
                        if (newCanvas) {
                            attachHandler(newCanvas);
                            observer.disconnect();
                        }
                    });
                    observer.observe($wrapper[0], {
                        childList: true,
                        subtree: true,
                    });
                }
            }
        }

        function initFullscreenFlowmap() {
            const $canvas = $('<div>', {
                id: 'wgl-flowmap-canvas',
            }).css({
                zIndex: 20,
                pointerEvents: 'none',
                width: '100vw',
                height: '100vh',
                transition: 'opacity .5s ease-in',
                position: 'fixed',
                top: 0,
                left: 0,
            });
            $('body').append($canvas);

            const curtains = new Curtains({
                container: $canvas[0],
                pixelRatio: Math.min(1.5, window.devicePixelRatio),
                autoRender: false
            });

            window.wglCurtains.push({
                container: jQuery(this),
                instance: curtains,
                itemID: $canvas[0],
            });

            gsap.ticker.add(curtains.render.bind(curtains));

            const selector =
                '.wgl-webgl-plane_wrapper[data-image-effect="flowmap"]';
            jQuery(selector).each(function (index, element) {
                const $el = $(element);
                const mouse = new Vec2();
                const lastMouse = mouse.clone();
                const velocity = new Vec2();

                const planes = [];

                $el.find('img').each(function () {
                    const $img = $(this);
                    $img.attr({
                        crossorigin: '',
                        'data-sampler': 'planeTexture',
                    });

                    const $wrapperElement = $('<div>', {
                        class: 'wgl-webgl-plane',
                    });
                    $img.before($wrapperElement);
                    $wrapperElement.append($img);
                });

                const $planeElements = $el.find('.wgl-webgl-plane');

                const vs = `
                precision mediump float;
                attribute vec3 aVertexPosition;
                attribute vec2 aTextureCoord;
                uniform mat4 uMVMatrix;
                uniform mat4 uPMatrix;
                uniform mat4 planeTextureMatrix;
                varying vec3 vVertexPosition;
                varying vec2 vTextureCoord;
                uniform vec2 uMousePosition;
                uniform float uTime;
                uniform float uTransition;
                void main() {
                    vec3 vertexPosition = aVertexPosition;
                    float transition = 1.0 - abs((uTransition * 2.0) - 1.0);
                    float distanceFromMouse = distance(uMousePosition, vec2(vertexPosition.x, vertexPosition.y));
                    float waveSinusoid = cos(5.0 * (distanceFromMouse - (uTime / 30.0)));
                    float distanceStrength = (0.4 / (distanceFromMouse + 0.4));
                    float distortionEffect = distanceStrength * waveSinusoid * 0.33;
                    vertexPosition.z += distortionEffect * -transition;
                    vertexPosition.x += (distortionEffect * transition * (uMousePosition.x - vertexPosition.x));
                    vertexPosition.y += distortionEffect * transition * (uMousePosition.y - vertexPosition.y);
                    gl_Position = uPMatrix * uMVMatrix * vec4(vertexPosition, 1.0);
                    vTextureCoord = (planeTextureMatrix * vec4(aTextureCoord, 0.0, 1.0)).xy;
                    vVertexPosition = vertexPosition;
                }
            `;

                const fs = `
                precision mediump float;
                varying vec3 vVertexPosition;
                varying vec2 vTextureCoord;
                uniform sampler2D planeTexture;
                void main() {
                    vec4 finalColor = texture2D(planeTexture, vTextureCoord);
                    finalColor.rgb += clamp(vVertexPosition.z, -1.0, 0.0) * 0.75;
                    finalColor.rgb += clamp(vVertexPosition.z, 0.0, 1.0) * 0.75;
                    gl_FragColor = finalColor;
                }
            `;

                const params = {
                    vertexShader: vs,
                    fragmentShader: fs,
                    widthSegments: 10,
                    heightSegments: 10,
                    uniforms: {
                        time: {
                            name: 'uTime',
                            type: '1f',
                            value: 0,
                        },
                        fullscreenTransition: {
                            name: 'uTransition',
                            type: '1f',
                            value: 0,
                        },
                        mousePosition: {
                            name: 'uMousePosition',
                            type: '2f',
                            value: mouse,
                        },
                    },
                };

                $planeElements.each(function (i) {
                    const plane = new Plane(curtains, this, params);
                    planes.push(plane);
                    handlePlanes(i);
                });

                function handlePlanes(index) {
                    const plane = planes[index];
                    plane
                        .onReady(() => {
                            plane.textures[0].setScale(new Vec2(1, 1));
                            curtains.resize();
                            const rect = plane.getBoundingRect();
                        })
                        .onAfterResize(() => {
                            if (plane.userData.isFullscreen) {
                                const planeBoundingRect =
                                    plane.getBoundingRect();
                                const curtainBoundingRect =
                                    curtains.getBoundingRect();
                                plane.setScale(
                                    new Vec2(
                                        curtainBoundingRect.width /
                                            planeBoundingRect.width,
                                        curtainBoundingRect.height /
                                            planeBoundingRect.height
                                    )
                                );
                                plane.setRelativeTranslation(
                                    new Vec3(
                                        (-1 * planeBoundingRect.left) /
                                            curtains.pixelRatio,
                                        (-1 * planeBoundingRect.top) /
                                            curtains.pixelRatio,
                                        0
                                    )
                                );
                            }
                        })
                        .onRender(() => {
                            plane.uniforms.time.value++;
                        });
                }

                function onMouseMove(e) {
                    lastMouse.copy(mouse);
                    if (e.originalEvent.targetTouches) {
                        mouse.set(
                            e.originalEvent.targetTouches[0].clientX,
                            e.originalEvent.targetTouches[0].clientY
                        );
                    } else {
                        mouse.set(e.clientX, e.clientY);
                    }
                    velocity.set(
                        (mouse.x - lastMouse.x) / 16,
                        (mouse.y - lastMouse.y) / 16
                    );
                    updateVelocity = true;
                }

                $(window).on('mousemove touchmove', onMouseMove);

                let updateVelocity = false;

                const flowmapVs = `
                #ifdef GL_FRAGMENT_PRECISION_HIGH
                precision highp float;
                #else
                precision mediump float;
                #endif
                attribute vec3 aVertexPosition;
                attribute vec2 aTextureCoord;
                uniform mat4 uMVMatrix;
                uniform mat4 uPMatrix;
                varying vec3 vVertexPosition;
                varying vec2 vTextureCoord;
                void main() {
                    vec3 vertexPosition = aVertexPosition;
                    gl_Position = uPMatrix * uMVMatrix * vec4(vertexPosition, 1.0);
                    vTextureCoord = aTextureCoord;
                    vVertexPosition = vertexPosition;
                }
            `;

                const flowmapFs = `
                #ifdef GL_FRAGMENT_PRECISION_HIGH
                precision highp float;
                #else
                precision mediump float;
                #endif
                varying vec3 vVertexPosition;
                varying vec2 vTextureCoord;
                uniform sampler2D uFlowMap;
                uniform vec2 uMousePosition;
                uniform float uFalloff;
                uniform float uAlpha;
                uniform float uDissipation;
                uniform vec2 uVelocity;
                uniform float uAspect;
                void main() {
                    vec2 textCoords = vTextureCoord;    
                    vec4 color = texture2D(uFlowMap, textCoords) * uDissipation;
                    vec2 mouseTexPos = (uMousePosition + 1.0) * 0.5;
                    vec2 cursor = vTextureCoord - mouseTexPos;
                    cursor.x *= uAspect;
                    vec3 stamp = vec3(uVelocity * vec2(1.0, -1.0), 1.0 - pow(1.0 - min(1.0, length(uVelocity)), 3.0));
                    float falloff = smoothstep(uFalloff, 0.0, length(cursor)) * uAlpha;
                    color.rgb = mix(color.rgb, stamp, vec3(falloff));
                    gl_FragColor = color;
                }
            `;

                const bbox = curtains.getBoundingRect();

                const flowMapParams = {
                    sampler: 'uFlowMap',
                    vertexShader: flowmapVs,
                    fragmentShader: flowmapFs,
                    watchScroll: false,
                    texturesOptions: {
                        floatingPoint: 'half-float',
                    },
                    uniforms: {
                        mousePosition: {
                            name: 'uMousePosition',
                            type: '2f',
                            value: mouse,
                        },
                        fallOff: {
                            name: 'uFalloff',
                            type: '1f',
                            value:
                                bbox.width > bbox.height
                                    ? bbox.width / 15000
                                    : bbox.height / 15000,
                        },
                        alpha: {
                            name: 'uAlpha',
                            type: '1f',
                            value: 1,
                        },
                        dissipation: {
                            name: 'uDissipation',
                            type: '1f',
                            value: 0.975,
                        },
                        velocity: {
                            name: 'uVelocity',
                            type: '2f',
                            value: velocity,
                        },
                        aspect: {
                            name: 'uAspect',
                            type: '1f',
                            value: bbox.width / bbox.height,
                        },
                    },
                };

                const flowMap = new PingPongPlane(
                    curtains,
                    curtains.container,
                    flowMapParams
                );

                flowMap
                    .onRender(() => {
                        flowMap.uniforms.mousePosition.value =
                            flowMap.mouseToPlaneCoords(mouse);
                        if (!updateVelocity) {
                            velocity.set(
                                curtains.lerp(velocity.x, 0, 0.5),
                                curtains.lerp(velocity.y, 0, 0.5)
                            );
                        }
                        updateVelocity = false;
                        flowMap.uniforms.velocity.value = new Vec2(
                            curtains.lerp(velocity.x, 0, 0.1),
                            curtains.lerp(velocity.y, 0, 0.1)
                        );
                    })
                    .onAfterResize(() => {
                        const boundingRect = flowMap.getBoundingRect();
                        flowMap.uniforms.aspect.value =
                            boundingRect.width / boundingRect.height;
                        flowMap.uniforms.fallOff.value =
                            boundingRect.width > boundingRect.height
                                ? boundingRect.width / 15000
                                : boundingRect.height / 15000;
                    });

                const displacementVs = `
                #ifdef GL_FRAGMENT_PRECISION_HIGH
                precision highp float;
                #else
                precision mediump float;
                #endif
                attribute vec3 aVertexPosition;
                attribute vec2 aTextureCoord;
                varying vec3 vVertexPosition;
                varying vec2 vTextureCoord;
                void main() {
                    gl_Position = vec4(aVertexPosition, 1.0);
                    vTextureCoord = aTextureCoord;
                    vVertexPosition = aVertexPosition;
                }
            `;

                const displacementFs = `
                #ifdef GL_FRAGMENT_PRECISION_HIGH
                precision highp float;
                #else
                precision mediump float;
                #endif
                varying vec3 vVertexPosition;
                varying vec2 vTextureCoord;
                uniform sampler2D uRenderTexture;
                uniform sampler2D uFlowTexture;
                void main() {
                    vec4 flowTexture = texture2D(uFlowTexture, vTextureCoord);
                    vec2 distortedCoords = vTextureCoord;
                    distortedCoords -= flowTexture.xy * 0.1;
                    vec4 texture = texture2D(uRenderTexture, distortedCoords);
                    vec4 rTexture = texture2D(uRenderTexture, distortedCoords + flowTexture.xy * 0.0125);
                    vec4 gTexture = texture2D(uRenderTexture, distortedCoords);
                    vec4 bTexture = texture2D(uRenderTexture, distortedCoords - flowTexture.xy * 0.0125);
                    float mixValue = clamp((abs(flowTexture.r) + abs(flowTexture.g) + abs(flowTexture.b)) * 1.5, 0.0, 1.0);
                    texture = mix(texture, vec4(rTexture.r, gTexture.g, bTexture.b, texture.a), mixValue);
                    gl_FragColor = texture;
                }
            `;

                const passParams = {
                    vertexShader: displacementVs,
                    fragmentShader: displacementFs,
                    depth: false,
                };

                const shaderPass = new ShaderPass(curtains, passParams);

                const flowTexture = shaderPass.createTexture({
                    sampler: 'uFlowTexture',
                    floatingPoint: 'half-float',
                    fromTexture: flowMap.getTexture(),
                });

                flowTexture.onSourceUploaded(() => {
                    const fxaaPass = new FXAAPass(curtains);
                });

                const resizeObserver = new ResizeObserver(() => {
                    curtains.resize();
                });
                resizeObserver.observe(document.body);
            });
        }
        jQuery(document).trigger('wglCurtainsReady');
    }

    jQuery(window).on('load', function () {
        initWGLCurtains();
    });

	jQuery(document).off('click.wglLightbox', '.wgl-webgl-plane_wrapper').on('click.wglLightbox', '.wgl-webgl-plane_wrapper', function (e) {
		const $this = jQuery(this);
		let $lightbox;

        const $childLinks = $this.find('a.portfolio_link');
        if ($childLinks.length) {
            e.stopPropagation();
            $childLinks.get(0).click();
            return;
        }

		if (jQuery(e.target).is('[data-elementor-open-lightbox="yes"]')) {
			return; 
		}

		const $childLightbox = $this.find('[data-elementor-open-lightbox="yes"]');

		if ($childLightbox.length) {
			$lightbox = $childLightbox;
		} else {
			$lightbox = $this.parent().find('[data-elementor-open-lightbox="yes"]');
		}

		if ($lightbox.length) {
			e.stopPropagation();
			$lightbox.trigger('click');
		}
	});

    window.reinitWGLCurtains = function ($context) {
        initWGLCurtains($context || $(document));
    };

})(jQuery);