@extends('app')

@section('content')
                <label><h2>Services</h2></label>

                    <div class="accordion with-marker" data-role="accordion" data-closeany="true">
                        <?php $count = 0 ?>
                        @foreach($entities as $entity)
                        <!-- Start accordion frame -->
                        <div class="accordion-frame @if($count==0)  active @endif">
                            <a class="heading collapsed" href="#">{{$entity}}</a>
                            <div class="content clearfix">
                                <table class="table stripped hovered">
                                    <thead>
                                    <tr>
                                        <th>Service</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($entity->myServices() as $service)
                                        <tr>
                                            <td>{{$service}}</td>
                                            <td><a class="button warning" href="{{route('get.apply.service',['form'=>@$service->group->form->FormID,'ServiceNo'=>@$service->id()])}}">Apply</a> </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!--  End of accordion frame -->
                            <?php $count +=1; ?>
                       @endforeach
                    </div>

@endsection
