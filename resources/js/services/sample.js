import { apiUrl } from "@/constants/config";
import Api from "@/utils/api";

export function getSampleList(params) {
    return new Promise((resolve, reject) => {
        Api.post(apiUrl + '/docs/template/list', params).then(response => {
            resolve(response.data)
        }).catch((e) => {
            reject('Ошибка при загрузке')
        })
    })
}

export function storeSample(params) {
    return new Promise((resolve, reject) => {
        Api.post(apiUrl + '/docs/template/store', params, {headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
            resolve(response.data)
        }).catch((e) => {
            reject(e.response.data)
        })
    })
}
