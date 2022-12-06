import { apiUrl } from "../constants/config";
import Api from "../utils/api";

export function getKnowledgeList(params) {
    return new Promise((resolve, reject) => {
        Api.post(apiUrl + '/docs/regulation/list', params).then(response => {
            resolve(response.data)
        }).catch((e) => {
            reject('Ошибка при загрузке')
        })
    })
}


export function storeKnowledge(params) {
    return new Promise((resolve, reject) => {
        Api.post(apiUrl + '/docs/regulation/store', params, {headers: {'Content-Type': 'multipart/form-data'}}).then(response => {
            resolve(response.data)
        }).catch((e) => {
            reject(e.response.data)
        })
    })
}

export function getKnowledgeTypes() {
    return new Promise((resolve, reject) => {
        Api.post(apiUrl + '/docs/type/all').then(response => {
            resolve(response.data)
        }).catch((e) => {
            reject('Ошибка при загрузке')
        })
    })
}

export function getKnowledgeAgency() {
    return new Promise((resolve, reject) => {
        Api.post(apiUrl + '/docs/agency/all').then(response => {
            resolve(response.data)
        }).catch((e) => {
            reject('Ошибка при загрузке')
        })
    })
}
