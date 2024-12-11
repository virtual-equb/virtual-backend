@can('draw equb_type_winner')
<div class="modal fade" id="drawModal" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <form role="form" method="post" class="form-horizontal" action="{{ route('drawAutoWinners') }}" enctype="multipart/form-data" id="drawEqubType">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h4 class="modal-title"> Draw</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group required">
                        <label class="control-label">Draw Type</label>
                        <select class="custom-select form-control" id="draw_type" name="draw_type" required>
                            <option selected value="">Choose Draw Type</option>
                            <option value="Automatic">Automatic</option>
                            <option value="Seasonal">Seasonal</option>
                        </select>
                    </div>
                    <div class="form-group required">
                        <label class="control-label">Equb Type</label>
                        <select class="form-control select2" id="equbTypeId" name="equbTypeId" required>
                            <option selected value="">Choose Equb Type</option>
                            @foreach ($equbTypes as $equbType)
                                <option data-info="{{ $equbType->type }}"
                                        data-startdate="{{ $equbType->start_date }}"
                                        data-enddate="{{ $equbType->end_date }}"
                                        data-rote="{{ $equbType->rote }}" 
                                        data-quota="{{ $equbType->quota }}"
                                        value="{{ $equbType->id }}">
                                    {{ $equbType->name }} round {{ $equbType->round }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" onclick="drawAutoWinners()">Draw</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endcan
<div class="modal fade" id="myModal" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                        <form role="form" method="post" class="form-horizontal form-group"
                            action="{{ route('registerEqubType') }}" enctype="multipart/form-data" id="addEqubType">
                            {{ csrf_field() }}
                            <div class="modal-header">
                                <h4 class="modal-title">Add Equb Type </h4>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="col-sm-12">
                                    <div class="form-group required">
                                        <label class="control-label">Equb</label>
                                        <select class="custom-select form-control" id="main_equb_id" name="main_equb_id">
                                            <option selected value="">Choose Equb</option>
                                            @if(isset($mainEqubs) && count($mainEqubs) > 0)
                                                @foreach($mainEqubs as $equb)
                                                    <option value="{{ $equb->id }}">{{ $equb->name }}</option>
                                                @endforeach
                                            @else
                                                <option disabled>No Equbs Available</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group required">
                                        <label class="control-label">Type</label>
                                        <select class="custom-select form-control" id="type" name="type">
                                            <option selected value="">Choose Type</option>
                                            <option value="Automatic">Automatic</option>
                                            <option value="Manual">Manual</option>
                                            <option value="Seasonal">Seasonal</option>
                                        </select>
                                    </div>
                                    <div class="form-group required">
                                        <label class="control-label">Name</label>
                                        <input type="text" class="form-control" id="name"
                                            name="name"placeholder="Name" required>
                                    </div>
                                    <div class="form-group required">
                                        <label class="control-label">Round</label>
                                        <input type="number" class="form-control" id="round"
                                            name="round"placeholder="Round" min="1" required>
                                    </div>
                                    <div class="form-group required">
                                        <label class="control-label">Rote</label>
                                        <select class="custom-select form-control" id="rote" name="rote">
                                            <option selected value="">Choose Rote</option>
                                            <option value="Daily" id="daily_rote">Daily</option>
                                            <option value="Weekly" id="weekly_rote">Weekly</option>
                                            {{-- <option value="Biweekly" id="biweekly_rote">Biweekly</option> --}}
                                            <option value="Monthly" id="monthly_rote">Monthly</option>
                                        </select>
                                    </div>
                                    <div id="start_date_div" class="form-group d-none">
                                        <label for="start_date" class="control-label">Start Date</label>
                                        <input type="date" class="form-control" id="start_date" name="start_date"
                                            placeholder="Start Date" autocomplete="off">
                                    </div>
                                    <div id="quota_div" class="form-group d-none">
                                        <label class="control-label">Quota</label>
                                        <input type="number" class="form-control" id="quota" name="quota"
                                            placeholder="Quota" min="1" required>
                                    </div>
                                    <div id="end_date_div" class="form-group d-none">
                                        <label for="end_date" class="control-label">End Date</label>
                                        <input type="text" class="form-control" id="end_date" name="end_date"
                                            placeholder="End Date" autocomplete="off" readonly>
                                    </div>
                                    <div id="lottery_date_div" class="form-group d-none">
                                        <label for="lottery_date" class="control-label">Lottery Date</label>
                                        <input type="text" class="form-control" id="lottery_date" name="lottery_date"
                                            placeholder="Lottery Date" autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Icon</label>
                                        <input type="file" class="form-control" name="icon"
                                            accept="image/jpeg, image/png">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Remark</label>
                                        <textarea class="form-control" id="remark" name="remark" placeholder="remark"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Terms and Conditions</label>
                                        <textarea class="form-control textareaa" id="terms" name="terms" placeholder="terms"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary"
                                    onclick="addEqubTypeValidation()">Save</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
            <div class="modal fade" id="drawModal" role="dialog">
                <div class="modal-dialog">

                    <!-- Modal content-->
                    <div class="modal-content">
                    <form role="form" method="post" class="form-horizontal" action="{{ route('registerEqub') }}" enctype="multipart/form-data" id="addEqub">
                {{ csrf_field() }}
                <div class="modal-header">
                    <h4 class="modal-title">Add Equb</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="col-sm-12">
                        <input type="hidden" id="member_id" name="member_id" value="">
                        <div class="form-group required">
                            <label class="control-label">Type</label>
                            <select class="custom-select form-control" id="type" name="type" required onchange="filterEqubTypes()">
                                <option selected value="">Choose Type</option>
                                <option value="Automatic">Automatic</option>
                                <option value="Seasonal">Seasonal</option>
                                <option value="Manual">Manual</option>
                            </select>
                        </div>
                        <div class="form-group required">
                            <label class="control-label">Equb Type</label>
                            <select class="form-control select2" id="equb_type_id" name="equb_type_id" placeholder="Equb Type">
                                <option value="">choose...</option>
                                @foreach ($equbTypes as $equbType)
                                    <option data-info="{{ $equbType->type }}"
                                        data-startdate="{{ $equbType->start_date }}"
                                        data-enddate="{{ $equbType->end_date }}" 
                                        data-rote="{{ $equbType->rote }}" 
                                        data-quota="{{ $equbType->quota }}"
                                        data-amount="{{ $equbType->amount }}" 
                                        data-expected-total="{{ $equbType->expected_total }}" 
                                        value="{{ $equbType->id }}">
                                        {{ $equbType->name }} round {{ $equbType->round }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group required">
                            <label class="control-label">Start Date</label>
                            <input type="text" class="form-control" id="start_date" name="start_date" placeholder="Start date" autocomplete="off">
                        </div>
                        <div class="form-group required">
                            <label class="control-label">End Date</label>
                            <input type="text" class="form-control" id="end_date" name="end_date" placeholder="End date" readonly>
                        </div>
                        <div class="form-group">
                            <label class="control-label">Amount</label>
                            <input type="number" class="form-control" id="amount_per_day" name="amount" placeholder="Amount" required>
                        </div>
                        <div class="form-group required">
                            <label class="control-label">Expected Total</label>
                            <input type="number" class="form-control" id="total_amount" name="total_amount" placeholder="Total equb amount" readonly>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </form>

                    </div>

                </div>
            </div>
<script>
    document.getElementById('draw_type').addEventListener('change', function() {
        var selectedType = this.value; // Get the selected draw type
        var equbTypeSelect = document.getElementById('equbTypeId'); // Get the "Equb Type" select element
        var form = document.getElementById('drawEqubType'); // Get the form element

        // Clear existing options in the "Equb Type" dropdown
        equbTypeSelect.innerHTML = '<option selected value="">Choose Equb Type</option>';

        // Get the equb types from the original options (passed from Laravel to JS)
        var equbTypes = @json($equbTypes); // Pass the PHP array to JavaScript

        // Loop through the equb types and filter them based on the selected draw type
        equbTypes.forEach(function(equbType) {
            // Check if the current equbType matches the selected draw type
            if (equbType.type === selectedType) {
                // Create a new option element for the matching equb type
                var option = document.createElement('option');
                option.value = equbType.id; // Set the value to the equbType ID
                option.setAttribute('data-info', equbType.type);
                option.setAttribute('data-startdate', equbType.start_date);
                option.setAttribute('data-enddate', equbType.end_date);
                option.setAttribute('data-rote', equbType.rote);
                option.setAttribute('data-quota', equbType.quota);
                
                // Set the text content to display the name and round information
                option.textContent = equbType.name + ' round ' + equbType.round;
                
                // Append the newly created option to the "Equb Type" select element
                equbTypeSelect.appendChild(option);
            }
        });

        // Change the form action based on the selected draw type
        if (selectedType === 'Seasonal') {
            form.action = "{{ route('drawAutoSeasonal') }}"; 
        } else {
            form.action = "{{ route('drawAutoWinners') }}";
        }
    });
</script>