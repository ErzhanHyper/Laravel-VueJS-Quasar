<template>
  <div>
    <div class="quotes" v-if="quote">
      <div class="subtitle-2 mb-2"><i>Цитата дня</i></div>
      <div class="quote">
        <i><q>{{ quote }}</q></i>
      </div>
    </div>
  </div>
</template>

<script>
import Api from "@/utils/api";

export default {

  data(){
    return {
      quote:''
    }
  },

  methods:{
    getQuoteLast(){
      Api.post('quote/getLast').then(res => {
          console.log(res)
        this.quote = res.data.text
      })
    }
  },

  mounted(){
    this.getQuoteLast()
  }
}
</script>

<style scoped>

.quotes{
  margin: 20px;
  background: #de844d1a;
  padding: 10px;
  border-radius: 10px;
}
.quote{
  padding: 10px;
  border:1px dashed lightgray;
  position:relative;
  border-radius: 10px;
  font-family: Candara;
}

</style>
