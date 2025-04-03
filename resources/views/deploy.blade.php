<!-- resources/views/deploy.blade.php -->
<form id="deploy-form" method="POST" action="{{ route('deploy') }}">
    @csrf
    <label for="repo_url">Git Repository URL:</label>
    <input type="text" name="repo_url" id="repo_url" required>

    <label for="branch">Select Branch:</label>
    <select name="branch" id="branch" required>
        <option value="">-- Select a Branch --</option>
    </select>

    <label for="ssh_user">SSH Username:</label>
    <input type="text" name="ssh_user" id="ssh_user" required>

    <label for="ssh_password">SSH Password:</label>
    <input type="password" name="ssh_password" id="ssh_password" required>

    <label for="server_address">Server Address (IP or Domain):</label>
    <input type="text" name="server_address" id="server_address" required>

    <label for="deploy_path">Deployment Path on Server:</label>
    <input type="text" name="deploy_path" id="deploy_path" required>

    <button type="submit">Deploy</button>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#repo_url').on('blur', function() {
        var repoUrl = $(this).val();
        if (repoUrl) {
            $.ajax({
                url: '{{ route("getBranchesAjax") }}',
                type: 'GET',
                data: { repo_url: repoUrl },
                success: function(response) {
                    var branchesSelect = $('#branch');
                    branchesSelect.empty();
                    if (response.branches && response.branches.length > 0) {
                        $.each(response.branches, function(index, branch) {
                            branchesSelect.append('<option value="' + branch + '">' + branch + '</option>');
                        });
                    } else {
                        branchesSelect.append('<option value="">No branches found</option>');
                    }
                },
                error: function(xhr) {
                  console.log('Error fetching branches: ' + xhr.responseJSON.error);
                    alert('Error fetching branches: ' + xhr.responseJSON.error);
                    $('#branch').empty().append('<option value="">-- Select a Branch --</option>');
                }
            });
        }
    });
});
</script>
