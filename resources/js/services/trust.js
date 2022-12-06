import { apiUrl } from "@/constants/config";
import Api from "@/utils/api";

export function getTrustList(params) {
    return new Promise((resolve, reject) => {
        Api.post(apiUrl + '/docs/trust/list', params).then(response => {
            resolve(response.data)
        }).catch((e) => {
            reject('Ошибка при загрузке')
        })
    })
}
