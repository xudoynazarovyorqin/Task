require('./bootstrap');
import { createApp } from "vue"
import  mainPage  from "./components/mainPage"

const app = createApp({})

app.component('main-page',mainPage);
app.mount('#app')
