import { apiUrl } from "@/constants/config";
import Api from "@/utils/api";

export function getRoleList(params) {
    return new Promise((resolve, reject) => {
        Api.post(apiUrl + '/role/list', params).then(response => {
            resolve(response.data)
        }).catch((e) => {
            reject('Ошибка при загрузке')
        })
    })
}
