<template>
    <div>
        <header id="el-drawer__title" class="el-drawer__header">
            <span title="Новый заказ">
                Укажите помещения, для которых необходимо применить планировку
            </span>
            <el-button type="success" size="small" class="mr-1"> Создать</el-button>
            <el-button type="danger" icon="el-icon-close" size="small">Закрыть</el-button>
        </header>
        <div class="mark-chess-modal">
            <div class="left_s">
                <div class="squares namber">
                    <div class="title-square-four">№</div>
                    <div class="square-four">
                        <div>20</div>
                    </div>
                    <div class="square-four">
                        <div>19</div>
                    </div>
                    <div class="square-four">
                        <div>18</div>
                    </div>
                    <div class="square-four">
                        <div>17</div>
                    </div>
                    <div class="square-four">
                        <div>16</div>
                    </div>
                    <div class="square-four">
                        <div>15</div>
                    </div>
                    <div class="square-four">
                        <div>14</div>
                    </div>
                    <div class="square-four">
                        <div>13</div>
                    </div>
                    <div class="square-four">
                        <div>12</div>
                    </div>
                    <div class="square-four">
                        <div>11</div>
                    </div>
                    <div class="square-four">
                        <div>10</div>
                    </div>
                    <div class="square-four">
                        <div>9</div>
                    </div>
                    <div class="square-four">
                        <div>8</div>
                    </div>
                    <div class="square-four">
                        <div>7</div>
                    </div>
                    <div class="square-four">
                        <div>6</div>
                    </div>
                    <div class="square-four">
                        <div>5</div>
                    </div>
                    <div class="square-four">
                        <div>4</div>
                    </div>
                    <div class="square-four">
                        <div>3</div>
                    </div>
                    <div class="square-four">
                        <div>2</div>
                    </div>
                    <div class="square-four">
                        <div>1</div>
                    </div>
                </div>
            </div>
            <div class="content_s">
                <div class="squares mini_squares">
                    <div class="title-square-four">Секция 1</div>
                    <div class="square-four">
                        
                        <!-- <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div> -->
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                </div>
                <div class="squares mini_squares">
                    <div class="title-square-four">Секция 2</div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                </div>
                <div class="squares mini_squares">
                    <div class="title-square-four">Секция 3</div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                    <div class="square-four">
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">3</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">2</span></el-popover>
                        </div>
                        <div  @click="flat($event)">
                            <el-popover placement="top-start" title="№341,  41.23 м2"  trigger="hover"> <span slot="reference">1</span></el-popover>
                        </div>
                    </div>
                </div>
            </div>
            <div class="right_s">
                <div class="squares namber">
                    <div class="title-square-four">№</div>
                    <div class="square-four">
                        <div>20</div>
                    </div>
                    <div class="square-four">
                        <div>19</div>
                    </div>
                    <div class="square-four">
                        <div>18</div>
                    </div>
                    <div class="square-four">
                        <div>17</div>
                    </div>
                    <div class="square-four">
                        <div>16</div>
                    </div>
                    <div class="square-four">
                        <div>15</div>
                    </div>
                    <div class="square-four">
                        <div>14</div>
                    </div>
                    <div class="square-four">
                        <div>13</div>
                    </div>
                    <div class="square-four">
                        <div>12</div>
                    </div>
                    <div class="square-four">
                        <div>11</div>
                    </div>
                    <div class="square-four">
                        <div>10</div>
                    </div>
                    <div class="square-four">
                        <div>9</div>
                    </div>
                    <div class="square-four">
                        <div>8</div>
                    </div>
                    <div class="square-four">
                        <div>7</div>
                    </div>
                    <div class="square-four">
                        <div>6</div>
                    </div>
                    <div class="square-four">
                        <div>5</div>
                    </div>
                    <div class="square-four">
                        <div>4</div>
                    </div>
                    <div class="square-four">
                        <div>3</div>
                    </div>
                    <div class="square-four">
                        <div>2</div>
                    </div>
                    <div class="square-four">
                        <div>1</div>
                    </div>
                </div>
            </div>
        </div>  

    </div>
</template>

<script>
export default {
    data() {
      return {
        visible: false
      };
    },
    methods: {
        flat(e){
            console.log(e);

            e.target.classList.toggle('active1')
        }
    }
}
</script>

