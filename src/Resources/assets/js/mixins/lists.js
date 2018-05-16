export default {
    data(){
        return {
            searchForm: {},
            data: {
                data:[],
                page:{
                    total: 0,
                    current: 1,
                    page_size: 20
                }
            },
            columns:[],
            componentCurrent: '',
            componentRow: {}
        }
    },
    methods:{
        search(page){

        },
        current(type, row){
            this.componentCurrent = type
            this.componentRow = row
        },
        componentChange(){
            this.componentCurrent = ''
            this.componentRow = {}
            this.search(this.data.page.current)
        }
    }
}