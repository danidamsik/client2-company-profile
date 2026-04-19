import { h } from 'vue';

const stroke = {
    stroke: 'currentColor',
    'stroke-width': '2',
    'stroke-linecap': 'round',
    'stroke-linejoin': 'round',
};

const path = (d, attrs = {}) => h('path', { d, ...stroke, ...attrs });
const circle = (attrs = {}) => h('circle', { ...attrs, ...stroke });
const rect = (attrs = {}) => h('rect', { ...attrs, ...stroke });
const line = (attrs = {}) => h('line', { ...attrs, ...stroke });
const polyline = (points, attrs = {}) => h('polyline', { points, ...stroke, ...attrs });
const polygon = (points, attrs = {}) => h('polygon', { points, ...stroke, ...attrs });

const iconChildren = (name) => {
    switch (name) {
        case 'lock':
            return [
                rect({ x: '5', y: '11', width: '14', height: '10', rx: '2' }),
                path('M8 11V7a4 4 0 0 1 8 0v4'),
            ];
        case 'key':
            return [
                circle({ cx: '7.5', cy: '14.5', r: '3.5' }),
                path('M10 12 21 1M15 7l2 2M18 4l2 2'),
            ];
        case 'door-open':
            return [
                path('M14 3H6v18h8'),
                path('M14 3v18l5-2V5l-5-2Z'),
                path('M11 12h.01'),
            ];
        case 'camera':
            return [
                path('M4 7h3l2-2h6l2 2h3v12H4V7Z'),
                circle({ cx: '12', cy: '13', r: '3' }),
            ];
        case 'eye':
            return [
                path('M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z'),
                circle({ cx: '12', cy: '12', r: '3' }),
            ];
        case 'scan':
            return [
                path('M4 7V5a1 1 0 0 1 1-1h2M17 4h2a1 1 0 0 1 1 1v2M20 17v2a1 1 0 0 1-1 1h-2M7 20H5a1 1 0 0 1-1-1v-2'),
                line({ x1: '7', y1: '12', x2: '17', y2: '12' }),
            ];
        case 'radio':
            return [
                path('m8 7 8-4M7 8h10a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2Z'),
                path('M9 13h6M9 16h3'),
            ];
        case 'headset':
            return [
                path('M4 13v-1a8 8 0 0 1 16 0v1'),
                path('M4 13v4a2 2 0 0 0 2 2h2v-6H6a2 2 0 0 0-2 2ZM20 13v4a2 2 0 0 1-2 2h-2v-6h2a2 2 0 0 1 2 2Z'),
                path('M14 21h-2'),
            ];
        case 'phone':
            return [
                path('M22 16.9v3a2 2 0 0 1-2.2 2A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1.9.3 1.8.6 2.6a2 2 0 0 1-.4 2.1L8 9.7a16 16 0 0 0 6.3 6.3l1.3-1.3a2 2 0 0 1 2.1-.4c.8.3 1.7.5 2.6.6a2 2 0 0 1 1.7 2Z'),
            ];
        case 'route':
            return [
                path('M5 18c4 0 4-12 8-12s4 12 8 12M5 18h16'),
                path('M5 18a2 2 0 1 0 0 .1M21 18a2 2 0 1 0 0 .1'),
            ];
        case 'map-pin':
            return [
                path('M12 21s7-5.2 7-11a7 7 0 1 0-14 0c0 5.8 7 11 7 11Z'),
                circle({ cx: '12', cy: '10', r: '2.5' }),
            ];
        case 'car':
            return [
                path('M5 17h14l-1.5-5h-11L5 17ZM7 17v2M17 17v2'),
                path('M7 12l1.2-4h7.6L17 12'),
                circle({ cx: '8', cy: '17', r: '1' }),
                circle({ cx: '16', cy: '17', r: '1' }),
            ];
        case 'truck':
            return [
                path('M3 7h11v10H3V7ZM14 11h4l3 3v3h-7v-6Z'),
                circle({ cx: '7', cy: '18', r: '2' }),
                circle({ cx: '17', cy: '18', r: '2' }),
            ];
        case 'building':
            return [
                path('M6 20V9l6-5 6 5v11M9 20v-8h6v8'),
            ];
        case 'home':
            return [
                path('M3 11 12 4l9 7'),
                path('M5 10v10h14V10'),
                path('M9 20v-6h6v6'),
            ];
        case 'factory':
            return [
                path('M3 21V9l6 4V9l6 4V7h6v14H3Z'),
                path('M7 17h.01M11 17h.01M15 17h.01'),
            ];
        case 'warehouse':
            return [
                path('M3 21V9l9-5 9 5v12'),
                path('M7 21v-8h10v8'),
                path('M9 17h6'),
            ];
        case 'users':
            return [
                path('M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2'),
                circle({ cx: '9', cy: '7', r: '4' }),
                path('M22 21v-2a4 4 0 0 0-3-3.9M16 3.1a4 4 0 0 1 0 7.8'),
            ];
        case 'user-check':
            return [
                path('M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2'),
                circle({ cx: '9', cy: '7', r: '4' }),
                polyline('16 11 18 13 22 9'),
            ];
        case 'badge':
            return [
                polygon('12 3 15 8 20 8.7 16.4 12.2 17.2 17.3 12 14.9 6.8 17.3 7.6 12.2 4 8.7 9 8 12 3'),
            ];
        case 'clipboard':
            return [
                path('M9 4h6l1 2h2v15H6V6h2l1-2Z'),
                path('M9 12h6M9 16h4'),
            ];
        case 'alarm':
            return [
                path('M6 8a6 6 0 0 1 12 0c0 7 3 7 3 9H3c0-2 3-2 3-9'),
                path('M10 21h4M4 4 2 6M20 4l2 2'),
            ];
        case 'siren':
            return [
                path('M7 18v-6a5 5 0 0 1 10 0v6'),
                path('M5 18h14M10 4V2M4 8 2.5 6.5M20 8l1.5-1.5'),
                path('M9 14h6'),
            ];
        case 'fire':
            return [
                path('M12 22c4 0 7-2.8 7-6.8 0-3.4-2-5.7-4.2-7.4.2 2.2-.8 3.6-2 4.5.2-3-1-5.6-3.8-8.3.1 4-4 6.4-4 11.2C5 19.2 8 22 12 22Z'),
                path('M12 19c1.7 0 3-1.1 3-2.8 0-1.5-.9-2.4-2-3.2 0 1-.5 1.8-1.2 2.4 0-1.4-.5-2.6-1.8-3.7.1 1.9-1.5 3-1.5 4.8C8.5 18 10 19 12 19Z'),
            ];
        case 'hard-hat':
            return [
                path('M4 18h16'),
                path('M6 18v-3a6 6 0 0 1 12 0v3'),
                path('M9 15V9M15 15V9'),
            ];
        default:
            return [
                path('M12 3 19 5.5v5.2c0 4.5-2.8 8.7-7 10.3-4.2-1.6-7-5.8-7-10.3V5.5L12 3Z'),
            ];
    }
};

export default {
    props: {
        name: {
            type: String,
            default: 'shield',
        },
    },
    setup(props, { attrs }) {
        return () => h('svg', {
            ...attrs,
            viewBox: '0 0 24 24',
            fill: 'none',
            'aria-hidden': 'true',
        }, iconChildren(props.name));
    },
};
