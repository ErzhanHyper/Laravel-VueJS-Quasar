<template>
    <div>
        <section
            id="section"
            ref="el"
            style="width: 100%; height:calc(100vh - 200px); overflow: auto;"
            :style="{
        cursor: isDragging ? 'grabbing' : 'grab',
        scrollSnapType: isDragging ? '' : '',
      }"
            @mousedown="onMouseDown"
            @mouseup="onMouseUp"
        >

            <div class="zoom_management q-gutter-sm">
                <q-btn  color="primary" round  @click="zoomBlock('plus')"><q-icon name="zoom_in" size="md" /></q-btn>
                <q-btn  color="primary" round  @click="zoomBlock('minus')"> <q-icon name="zoom_out" size="md" /></q-btn>
            </div>

            <div class="structure mt-5">
                <div class="section-1">

                    <div class="block-items level-1">
                        <div class="child left">
                            <span class="block-item" @click="getDeaptmentEmployee(24)">Служба комплаенс</span>
                            <span class="block-item" @click="getDeaptmentEmployee(11)">Служба внутреннего аудита</span>
                        </div>
                        <span class="block-item center line-1 label" @click="getSenate()">
              Совет директоров
              <span class="line"></span>
              <span class="line"> </span>
              <span class="line"></span>
              <span class="line"></span>
            </span>
                        <div class="child right">
                            <span class="block-item" @click="getEmployee(9)">Корпоративный секретарь</span>
                        </div>
                    </div>

                    <div class="block-items level-1 last">
                        <div class="child left">
                            <span class="block-item" @click="getDeaptmentEmployee(30)">Служба по защите гос. секретов</span>
                            <span class="block-item" @click="getDeaptmentEmployee(12)">Служба безопасности</span>
                        </div>
                        <span class="block-item center line-1 label" @click="getEmployee(1)">Председатель Правления
             <span class="line"></span>
              <span class="line"> </span>
              <span class="line"></span>
              <span class="line"></span>
              <span class="line"></span>

              <span class="line"></span>
              <span class="line"></span>
              <span class="line"></span>
              <span class="line"></span>
              <span class="line"></span>

            </span>
                        <div class="child right">
                            <span class="block-item" @click="getDeaptmentEmployee(13)">Секретариат Председателя Правления (Пресс-служба)</span>
                            <span class="block-item" @click="getDeaptmentEmployee(31)">Служба управления рисками и внутреннего контроля</span>
                        </div>
                    </div>
                </div>

                <div class="section-2">
                    <div class="block-items level-2">
            <span class="block-item line-2 label" @click="getEmployee(2)">
              Управляющий директор
              <span class="line"></span>
              <span class="line"> </span>
              <span class="line"></span>
              <span class="line"></span>
              <span class="line"></span>
            </span>
                        <div class="child-wrapper">
                            <div class="child">
                                <span class="block-item " @click="getDeaptmentEmployee(3)">Департамент закупок</span>
                                <span class="block-item " @click="getDeaptmentEmployee(25)">Департамент административно-хозяйственной работы</span>
                                <span class="block-item " @click="getDeaptmentEmployee(27)">Департамент контроля за утилизационными платежами</span>
                            </div>
                        </div>

                    </div>

                    <div class="block-items level-2">
            <span class="block-item line-2 label" @click="getEmployee(3)">
              Заместитель Председателя Правления, член Правления
              <span class="line"></span>
              <span class="line"> </span>
              <span class="line"></span>
              <span class="line"></span>
              <span class="line"></span>
            </span>
                        <div class="child-wrapper">
                            <div class="child">
                                <span class="block-item " @click="getDeaptmentEmployee(1)">Департамент бухгалтерского учета, финансовой и налоговой отчетности</span>
                                <span class="block-item " @click="getDeaptmentEmployee(26)">Департамент бюджетного планирования</span>
                                <span class="block-item " @click="getDeaptmentEmployee(17)">Департамент корпоративных финансов и казначейства</span>
                            </div>
                        </div>

                    </div>

                    <div class="block-items level-2">
            <span class="block-item line-2 label" @click="getEmployee(4)">
              Заместитель Председателя Правления, член Правления
                 <span class="line"></span>
              <span class="line"> </span>
              <span class="line"></span>
              <span class="line"></span>
              <span class="line"></span>
            </span>
                        <div class="child-wrapper">
                            <div class="child">
                <span class="block-item "
                      @click="getDeaptmentEmployee(4)">Департамент стратегического планирования</span>
                                <span class="block-item " @click="getDeaptmentEmployee(8)">Департамент информационных технологий</span>
                                <span class="block-item " @click="getDeaptmentEmployee(19)">Департамент инвестиционных проектов и трансферта технологий</span>
                            </div>
                        </div>

                    </div>

                    <div class="block-items level-2">
            <span class="block-item line-2 label" @click="getEmployee(5)">
              Заместитель Председателя Правления, член Правления
              <span class="line"></span>
              <span class="line"> </span>
              <span class="line"></span>
              <span class="line"></span>
            </span>
                        <span class="block-item line-2 label" @click="getEmployee(6)">
              Управляющий директор
              <span class="line"></span>
              <span class="line"> </span>
              <span class="line"></span>
              <span class="line"></span>
            </span>

                        <div class="child-wrapper">

                            <div class="child">
                                <span class="block-item " @click="getDeaptmentEmployee(18)">Юридический департамент</span>
                                <span
                                    class="block-item " @click="getDeaptmentEmployee(5)">Департамент управления человеческими ресурсами и документационного обеспечения</span>
                                <span class="block-item "
                                      @click="getDeaptmentEmployee(2)">Департамент  претензионно-исковой работы</span>
                            </div>
                        </div>

                    </div>

                    <div class="block-items level-2 last">
            <span class="block-item line-2 label" @click="getEmployee(7)">Заместитель Председателя Правления, член Правления
              <span class="line"></span>
              <span class="line"></span>
              <span class="line"></span>
              <span class="line"></span>
              <span class="line"></span>
              <span class="line"></span>
              <span class="line"></span>
              <span class="line"></span>
              <span class="line"></span>
              <span class="line"></span>
            </span>
                        <div class="child-wrapper">
                            <div class="child ">
                                <span class="block-item " @click="getDeaptmentEmployee(21)">Департамент по организации обращения с отходами</span>
                                <span class="block-item " @click="getDeaptmentEmployee(20)">Департамент по управлению историческими отходами</span>
                            </div>
                            <div class="child ">
                <span class="block-item "
                      @click="getDeaptmentEmployee(22)">Департамент инвентаризации парниковых газов</span>
                                <span class="block-item "
                                      @click="getDeaptmentEmployee(23)">Департамент системы торговли выбросами</span>
                                <span class="block-item " @click="getDeaptmentEmployee(15)">Департамент моделирования, анализ и прогнозирования</span>
                                <span class="block-item "
                                      @click="getDeaptmentEmployee(7)">Департамент по мониторингу и ведению проектов</span>
                            </div>
                            <div class="child ">
                <span class="block-item main line-2 label" @click="getEmployee(8)">
                  Управляющий директор
                  <span class="line"></span>
                  <span class="line"></span>
                  <span class="line"></span>
                  <span class="line"></span>
                </span>
                                <span class="block-item " @click="getDeaptmentEmployee(6)">Департамент по организации обращения с ВЭТС и ВЭССХТ</span>
                                <span class="block-item " @click="getDeaptmentEmployee(14)">Департамент регулирования приема утилизационных платежей</span>
                            </div>
                        </div>
                    </div>


                </div>

            </div>

        </section>

    </div>
</template>

<script>
// import Api from "@/api";

export default {
    data() {
        return {
            employees: [],
            dialog: false,
            isDragging: false,
            cursorPos: [0, 0],
            el: null,
            counter: 0.65,
            loading:false,
        };
    },
    mounted() {
        window.addEventListener("mouseup", this.onMouseUp);
        let section = document.getElementById('section')
        section.scrollLeft = 280;
    },
    destroyed() {
        window.removeEventListener("mouseup", this.onMouseUp);
    },
    methods: {
        /** @param {MouseEvent} ev */
        onMouseDown(ev) {
            this.cursorPos = [ev.pageX, ev.pageY];
            this.isDragging = true;

            window.addEventListener("mousemove", this.onMouseHold);
        },

        /** @param {MouseEvent} ev */
        onMouseUp() {
            window.removeEventListener("mousemove", this.onMouseHold);
            this.isDragging = false;
        },

        /** @param {MouseEvent} ev */
        onMouseHold(ev) {
            ev.preventDefault();

            requestAnimationFrame(() => {
                const delta = [
                    ev.pageX - this.cursorPos[0],
                    ev.pageY - this.cursorPos[1],
                ];

                this.cursorPos = [ev.pageX, ev.pageY];

                if (!this.$refs.el) return;
                this.$refs.el.scrollBy({
                    left: -delta[0],
                    top: -delta[1],
                });
            });
        },

        sortProfession(data) {
            let res = data
            res.sort(function (a, b) {
                if (a.profession.position > b.profession.position) {
                    return 1;
                }
                if (a.profession.position < b.profession.position) {
                    return -1;
                }
                return 0;
            });
            return res;
        },

        zoomBlock(type){
            const block = document.querySelector('.structure');
            if(type === 'minus'){
                this.counter -= 0.05;
                block.style.zoom = this.counter;
            }else{
                this.counter += 0.05;
                block.style.zoom = this.counter;
            }
        },

        getDeaptmentEmployee(value) {
            this.dialog = true
            this.loading = true
            // Api.post('department/' + value + '/employee').then(res => {
            //     this.employees = this.sortProfession(res.data)
            //     this.loading = false
            // })
        },

        getEmployee(value) {
            this.employees = []
            let employee = {
                id: null,
                fullname: '',
                profession: {
                    name: ''
                }
            }

            this.dialog = true
            this.employees.push(employee)

        },

        getSenate() {
            this.dialog = true
            this.employees = []
        }
    },

};

</script>

<style scoped lang="scss">

.section-2 {
    display: flex;
    flex-direction: row;
    margin-top: 40px;
}

.block-items {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    margin-bottom: 30px;
    font-size: 14px;

    &.last.level-2 .child-wrapper {
        flex-direction: row;
        display: flex;
    }


    &.level-2 {
        flex-direction: column;

        .block-item {
            width: 200px;
            align-items: center;
            justify-content: center;
            display: flex;
        }

        .child {
            .block-item {
                height: 120px;
            }
        }
    }

    &.level-1 {
        .child {
            width: 560px;
        }
    }

    .block-item {
        padding: 5px 10px;
        border: 2px solid lightgray;
        border-radius: 7px;
        margin: 10px 40px;
        text-align: center;
        position: relative;

        .line {
            width: 2px;
            background: lightgray;
            height: 100px;
            display: block;
            position: absolute;

            &:before {
                content: '';
                display: block;
                width: 0;
                height: 0;
                border-left: 5px solid transparent;
                border-right: 5px solid transparent;
                border-top: 5px solid gray;
                position: absolute;
                left: -4px;
                bottom: 0;
            }
        }


        &.line-1 {
            .line:nth-child(1) {
                left: -43px;
                height: 81px;
                top: -24px;
                transform: rotate(90deg);

            }

            .line:nth-child(2) {
                left: -43px;
                height: 81px;
                top: 30px;
                transform: rotate(90deg);
            }

            .line:nth-child(3) {
                left: 320px;
                height: 81px;
                top: 4px;
                transform: rotate(-90deg);
            }

            .line:nth-child(4) {
                left: 140px;
                height: 48px;
                top: 90px;
                transform: rotate(0deg);
            }

            .line:nth-child(6) {
                left: 0px;
                height: 1400px;
                top: -580px;
                transform: rotate(90deg);

                &:before {
                    display: none;
                }
            }

            .line:nth-child(7) {
                left: -700px;
                height: 30px;
                top: 119px;
            }

            .line:nth-child(8) {
                left: -420px;
                height: 30px;
                top: 119px;
            }

            .line:nth-child(9) {
                left: -140px;
                height: 30px;
                top: 119px;
            }

            .line:nth-child(10) {
                left: 700px;
                height: 30px;
                top: 119px;
            }

        }

        &:hover {
            background: #47b853;
            color: #fff;
            transition: .3s;
        }

        &.center {
            padding: 33px 40px;
            width: 300px;
        }
    }

    .child {
        display: flex;
        flex-direction: column;
    }

    .child.left {
        float: left;
    }

    .child.right {
        float: right;
    }

    &.level-1.last {
        .line:nth-child(4) {
            left: 140px;
            height: 58px;
            top: 90px;
            transform: rotate(0deg);
        }

        .line:nth-child(5) {
            left: 320px;
            height: 81px;
            top: -23px;
            transform: rotate(-90deg);
        }

        .line:nth-child(3) {
            left: 320px;
            height: 81px;
            top: 32px;
            transform: rotate(-90deg);
        }

    }

}

.section-2 {
    .level-2.block-items {
        position: relative;

        &:nth-child(2) {
            .line:nth-child(2) {
                top: 36px;
                height: 460px;
            }
        }

        &:nth-child(3) {
            .line:nth-child(2) {
                top: 36px;
                height: 458px;
            }
        }

        &:nth-child(4) {
            .block-item.line-2:nth-child(1) {

                .line:nth-child(2) {
                    top: 36px;
                    height: 458px;
                }

                .line:nth-child(3) {
                    top: 480px;
                }

                .line:nth-child(4) {
                    top: 74px;
                    left: 100px;
                    height: 20px;
                    transform: rotate(0deg);
                }
            }

            .block-item.line-2:nth-child(2) {
                .line {
                    left: -10px;

                    &:nth-child(1) {
                        height: 15px;
                    }

                    &:nth-child(3) {
                        height: 15px;
                        top: 110px;
                    }

                    &:nth-child(4) {
                        height: 15px;
                        top: 256px;
                    }

                    &:nth-child(2) {
                        left: -18px;
                        height: 250px;
                    }
                }
            }
        }

        .line:nth-child(1) {
            left: -18px;
            transform: rotate(90deg);
            height: 30px;

            &:before {
                display: none;
            }
        }

        .line:nth-child(2) {
            left: -32px;
            top: 14px;
            height: 480px;

            &:before {
                display: none;
            }
        }

        .line:nth-child(3) {
            left: -18px;
            top: 200px;
            transform: rotate(-90deg);
            height: 30px;
        }

        .line:nth-child(4) {
            left: -18px;
            top: 340px;
            transform: rotate(-90deg);
            height: 30px;
        }

        .line:nth-child(5) {
            left: -18px;
            top: 480px;
            transform: rotate(-90deg);
            height: 30px;
        }

        .line:nth-child(6) {
            left: -18px;
            top: 624px;
            transform: rotate(-90deg);
            height: 30px;
        }

        .main {
            height: auto;
            margin-top: -45px;
        }

        &.last {
            left: 280px;

            .block-item:nth-child(1) {
                .line:nth-child(2) {
                    top: 38px;
                    height: 600px;
                }

                .line:nth-child(7) {
                    top: 22px;
                    left: -190px;
                    height: 135px;
                }

                .line:nth-child(8) {
                    top: 20px;
                    left: 380px;
                    height: 82px;
                }

                .line:nth-child(9) {
                    top: -70px;
                    left: 288px;
                    height: 182px;
                    transform: rotate(90deg);

                    &:before {
                        display: none;
                    }
                }

                .line:nth-child(10) {
                    top: -70px;
                    left: -95px;
                    height: 188px;
                    transform: rotate(90deg);

                    &:before {
                        display: none;
                    }
                }
            }

            .child:nth-child(3) {
                .line:nth-child(2) {
                    top: 16px;
                    height: 240px;
                }

                .line:nth-child(3) {
                    top: 100px;
                }

                .line:nth-child(4) {
                    top: 240px;
                }
            }

        }

        .child-wrapper {
            //margin-top: 60px;
            position: absolute;
            top: 160px;
        }
    }


}

.label {
    background: #eeeeee;
}

.employee {
    padding: 10px 20px;
    margin: 10px;
    background: #edffeb;
    border-radius: 4px;
    font-size: 18px;
    text-align: center;

    &:hover {
        background: #47b853;

        a {
            color: #fff;
        }
    }

    a {
        color: #404040;

        span {
            font-weight: 600;
            font-size: 14px;
        }
    }
}

.structure {
    width: 1400px;
    zoom: 0.64;
    position: relative;
}

.section-1 {
    left: 280px;
    position: relative;
}

.zoom_management{
    position: absolute;
    right: 50px;
    display: flex;
    flex-direction: column;
    top: 80px;
    z-index: 10;
}

</style>
