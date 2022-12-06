import axios from 'axios';
import { apiUrl } from '@/constants/config.js';
export default axios.create({
    baseURL: apiUrl,
    headers: {
        "X-Requested-With": "XMLHttpRequest",
        "Access-Control-Allow-Origin": "*",
        'Content-Type': 'application/json',
    }
});
