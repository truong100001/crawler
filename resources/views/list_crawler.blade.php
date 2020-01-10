<!DOCTYPE html>
<html>
<head>
    <title>Crawler</title>
    <meta charset="utf-8">
    <link rel="shortcut icon" type="images/png" href="https://apache.org/favicons/apple-touch-icon-60x60.png"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/app.css')}}">
</head>
<body>
<table class="table">
    <tr class="success">
        <th>ID</th>
        <th>Title</th>
        <th>Date</th>
        <th>Details</th>
        <th>Images</th>
    </tr>
    @foreach($items as $p)
        <tr>
            <td>{{ $p->id }}</td>
            <td>{{ $p->title }}</td>
            <td>{{ $p->date }}</td>
            <td>{{ ($p->details) }}</td>
            <td>
                <img src="{{ Asset($p->images) }}" alt="{{ $p->title }}" width="120" height="120">
            </td>
        </tr>
    @endforeach
</table>
<div class="text-center box-paginate" v-if="(totalItems / per_page) > 1">
    <div is="uib-pagination"
         :boundary-links="true"
         :force-ellipses="true"
         :rotate="true"
         :max-size="maxSize"
         :total-items="totalItems"
         :items-per-page="per_page"
         @change="getDataPaginate"
         v-model="paginate"
         class="pagination-sm"
         first-text="<<" previous-text="前" next-text="次" last-text=">>">
    </div>
</div>

<script src="/js/vue2x/vue.min.js"></script>
<script src="/js/vuejs-uib-pagination.js"></script>>

<script type="application/javascript">

    var appPaginate = new Vue({
        el:'#appPaginate',
        data:{
            paginate: {'totalItems': total_number_of_entries, 'currentPage': current_page,'numPages':total_number_of_page},
            per_page:per_page,
            totalItems:total_number_of_entries,
            maxSize: 20
        },
        methods:{
            getData:function(){
                $('body').loading('stop');
                $('body').loading({
                    'theme' : 'dark',
                    'message' : 'データ取得中...' // データ取得中...
                });
                page = this.paginate.currentPage;
                $('#formPage').find('input[name="page"]').val(page);
                $('#formPage').submit();
            }
        }
    })
</script>
</body>
</html>

