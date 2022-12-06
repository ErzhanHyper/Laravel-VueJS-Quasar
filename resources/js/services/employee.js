import { apiUrl } from "../constants/config";
import Api from "../utils/api";

export function getEmployeeList(params) {
    return new Promise((resolve, reject) => {
        Api.post(apiUrl + '/employee/list', params).then(response => {
            resolve(response.data)
        }).catch((e) => {
            reject('Ошибка при загрузке')
        })
    })
}

export function getEmployeeNames() {
    return new Promise((resolve, reject) => {
        Api.post(apiUrl + '/employee/names').then(response => {
            resolve(response.data.data)
        }).catch((e) => {
            reject('Ошибка при загрузке')
        })
    })
}

export function getDepartments() {
    return new Promise((resolve, reject) => {
        Api.post(apiUrl + '/department/all').then(response => {
            resolve(response.data)
        }).catch((e) => {
            reject('Ошибка при загрузке')
        })
    })
}
