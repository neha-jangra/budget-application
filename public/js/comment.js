$(document).ready(function () {
    //show unread comments of a project
    var projectId = $("#project_id").val();
    var authId = $("#auth_id").val();
    fetchUnreadCommentsCount(projectId);
    var urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get("active_tab") === "comments") {
        markCommentsAsRead(projectId);
    }

    var mentionTag = $("#mentionUserList");
    var mentionInput = $(".message-input");
    initializeMentions(mentionInput, sendMessage);
    var taggedUsersInput = $("#tagged_users");
    var attachmentPreview = $("#attachment-preview");
    var fileInput = $("#message_file_input");
    var sendMessageBtn = $(".send-message-btn");
    var files = [];
    var editFiles = {};
    var replyFiles = {};
    var removedFiles = [];
    var attachmentPreview = $("#attachment-preview");

    var socket = io("https://socket.crebos.online");
    disabledSendMessage();

    // Function to handle file input change
    function handleFileInputChange(e, filesArray, previewContainer) {
        var filesList = e.target.files;
        for (var i = 0; i < filesList.length; i++) {
            var file = filesList[i];
            var fileType = file.type.split("/")[0];
            var fileUrl = URL.createObjectURL(file);
            if (fileType != "image") {
                fileUrl = "/images/file.svg";
            }

            var fileIndex = filesArray.length; // Store the current length as index

            var previewElement = $(
                '<div class="attachment-item" data-index="' +
                    fileIndex +
                    '">' +
                    '<img src="' +
                    fileUrl +
                    '" alt="' +
                    file.name +
                    '">' +
                    '<span class="remove-attachment" data-index="' +
                    fileIndex +
                    '" data-file-id="' +
                    fileIndex +
                    '">' +
                    "<span>&times;</span>" +
                    "</span>" +
                    "</div>"
            );

            filesArray.push(file);
            previewContainer.append(previewElement);
        }
        // Clear the file input value to allow re-selection of the same file
        e.target.value = "";
        disabledSendMessage();
    }

    // Function to handle attachment removal
    function handleAttachmentRemoval(filesArray, previewContainer) {
        $(document).on("click", ".remove-attachment", function () {
            var $attachmentItem = $(this).parent(); // The parent is the attachment item
            var index = $attachmentItem.data("index"); // Get the index of the file to be removed
            var fileId = $attachmentItem.data("file-id"); // Get the file ID (if available)
            var commentId = $(this).data("comment-id"); // Get the file ID (if available)

            // Remove the file from the files array if it's a new file (no file ID)
            if (index !== undefined && filesArray[index]) {
                filesArray.splice(index, 1);
            }

            // Store the removed file ID if editing
            if (commentId && fileId) {
                if (!removedFiles[commentId]) {
                    removedFiles[commentId] = [];
                }
                removedFiles[commentId].push(fileId);
            }

            console.log("commentId", commentId);
            console.log("fileId", fileId);
            console.log("removedFiles", removedFiles);

            // Remove the preview element
            $attachmentItem.remove();

            // Update indices in the DOM to ensure they stay in sync
            previewContainer.find(".attachment-item").each(function (i) {
                $(this).data("index", i); // Update the data-index attribute
                $(this).find(".remove-attachment").data("index", i); // Update the data-index on the remove button
            });

            // Optionally, call a function to handle UI changes related to attachment removal
            disabledSendMessage();
        });
    }

    // Event handler for new message file input changes
    $("#message_file_input").on("change", function (e) {
        handleFileInputChange(e, files, attachmentPreview);
    });

    // Apply removal handler to new message, edit, and reply containers
    handleAttachmentRemoval(files, attachmentPreview);

    mentionInput.on("input", function () {
        disabledSendMessage();
    });

    $(document).click(function (event) {
        if (!$(event.target).closest(".position-relative").length) {
            mentionTag.hide();
        }
    });

    $(document).on("click", ".send-message-btn", function () {
        var messageContent = mentionInput.html();
        var taggedUserIds = taggedUsersInput.val();
        var parentId = $(this).data("comment-id");
        var filesToSend = files;

        if (parentId !== undefined) {
            messageContent = $("#replyInput" + parentId).html();
            taggedUserIds = "";
            filesToSend = replyFiles[parentId] || [];
        }
        sendMessage(parentId, messageContent, taggedUserIds, filesToSend);
        mentionInput.empty();
        mentionTag.hide();
        attachmentPreview.empty();
        files = [];
        taggedUsersInput.val("");
        disabledSendMessage();
    });

    $(document).on("click", ".reply-message-btn", function () {
        var parentId = $(this).data("comment-id");
        var replyInput = $("#replyInput" + parentId);
        var messageContent = replyInput.html().trim();
        var taggedUserIds = "";
        var filesToSend = replyFiles[parentId] || [];
        sendMessage(parentId, messageContent, taggedUserIds, filesToSend);
        replyInput.empty();
        replyFiles[parentId] = [];
        $(".comment-listing-text").removeClass("edit-message");
        removeEditMessageClass(parentId);
    });

    function updateTaggedUsers() {
        var taggedIds = [];
        mentionInput.find(".mention-span").each(function () {
            var id = $(this).data("user-id");
            if (id) {
                taggedIds.push(id);
            }
        });
        taggedUsersInput.val(taggedIds.join(","));
    }

    function disabledSendMessage() {
        if (
            files.length == 0 &&
            mentionInput.html() !== undefined &&
            mentionInput.html().trim() == ""
        ) {
            sendMessageBtn.attr("disabled", true).addClass("inactive-btn");
        } else {
            sendMessageBtn.attr("disabled", false).removeClass("inactive-btn");
        }
    }

    // Function to send a message via AJAX
    function sendMessage(
        parentId = null,
        messageContent,
        taggedUserIds,
        files
    ) {
        var messageFiles = files;
        var projectId = $("#project_id").val();
        var formData = new FormData();
        formData.append("messageContent", messageContent);
        formData.append("taggedUserIds", taggedUserIds);
        formData.append("projectId", projectId);
        if (parentId) {
            formData.append("parentId", parentId);
        }
        for (var i = 0; i < messageFiles.length; i++) {
            formData.append("messageFiles[]", messageFiles[i]);
        }
        $.ajax({
            url: "/comments/send",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                appendNewMessage(response);
                // Send message through socket
                sendMessageSockets(
                    projectId,
                    messageContent,
                    taggedUserIds,
                    messageFiles,
                    response
                );
                $("#replyInput" + parentId).html();
                mentionInput.empty();
                taggedUsersInput.val("");
                $("#fileInput").val("");
                files = [];
                disabledSendMessage();
            },
            error: function (response) {
                console.log("Error:", response);
            },
        });
    }

    // Function to send message details via socket
    function sendMessageSockets(
        projectId,
        messageContent,
        taggedUserIds,
        messageFiles,
        commentDetail
    ) {
        var attachments = [];
        for (var i = 0; i < messageFiles.length; i++) {
            (function (file) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    attachments.push({
                        file_name: file.name,
                        file_type: file.type,
                        file_data: e.target.result,
                    });
                };
                reader.readAsDataURL(file);
            })(messageFiles[i]);
        }

        var data = JSON.stringify({
            project: "ECNL",
            title: "ECNL comments",
            description: messageContent,
            data: {
                taggedUserIds: taggedUserIds,
                attachments: attachments,
                project_id: projectId,
                commentDetail: commentDetail,
            },
        });

        $.ajax({
            url: "https://socket.crebos.online/send-socket",
            method: "POST",
            headers: {
                Authorization:
                    "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjJhYmUyYjUyLWQ3ZjAtNDNkNi1hYzFjLTE0OWQ2N2ExNTQxMyIsInVzZXJuYW1lIjoiY3JlYm9zQWRtaW5VU2VyIiwiZW1haWwiOiJhZG1pbkBjcmVib3MuY29tIiwiaWF0IjoxNzAyNTM4NTYwfQ.dHdoRlT8RWk_xn75T98xnFRorQVR55_Ud0sY8hwvFO4",
                "Content-Type": "application/json",
            },
            data: data,
            success: function (response) {
                console.log(JSON.stringify(response));
            },
            error: function (xhr, status, error) {
                console.log(error);
            },
        });
    }
    listenToSocket();

    // Function to listen for incoming socket messages and update UI
    function listenToSocket() {
        try {
            console.log("socket", socket);
            if (!socket.connected) {
                console.log("Socket not connected, reconnecting...");
                socket.connect();
            }
            socket.on("socketEvent-ECNL", function (message) {
                if (message.data.delete_id) {
                    $(
                        'div[data-delete-id="' + message.data.delete_id + '"]'
                    ).remove();
                }
                var response = message.data.commentDetail;

                if (projectId != message.data.project_id) {
                    return;
                }
                if (authId != response.user.id) {
                    appendNewMessage(response);
                }
            });
        } catch (error) {
            console.log("error", error);
        }
    }

    function appendNewMessage(response) {
        const imageExtensions = new Set([
            "jpg",
            "jpeg",
            "png",
            "gif",
            "bmp",
            "webp",
        ]);
        var isOwner = authId == response.user.id;
        // Determine if the comment is a reply (has a parent_id)
        var isReply = !!response.comment.parent_id;

        // Update UI with the new message
        var commentHtml = `
            <div class="d-flex flex-start mt-4 ${
                isReply ? "reply" : ""
            } comment-container" id="comment-${response.comment.id}">
                <div class="rounded-circle me-12">
                    <div class="username-initials">${nameInitialByName(
                        response.user.name
                    )}</div>
                </div>
                <div class="flex-grow-1 flex-shrink-1">
                    <div>
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="mb-0 text-md font-semibold text-gray-800">
                                ${response.user.name} ${
            isOwner
                ? '<span class="font-regular text-gray-500">(You)</span>'
                : ""
        }
                            </p>
                            <p class="mb-0 text-sm font-regular text-gray-500">
                                ${new Date(response.comment.created_at)
                                    .toLocaleString("en-US", {
                                        year: "numeric",
                                        month: "short",
                                        day: "2-digit",
                                        hour: "2-digit",
                                        minute: "2-digit",
                                        hour12: true,
                                    })
                                    .replace(",", "")
                                    .replace("at", "at")}
                            </p>
                        </div>
                        <div class="comment-listing-text">
                            ${
                                response.comment.content
                                    ? `
                                <div class="text-md font-regular text-gray-800 ${
                                    isReply ? "" : "comment-content"
                                }">
                                    ${response.comment.content}
                                </div>
                            `
                                    : ""
                            }
                            <div class="comment-attachment" data-comment-id="${
                                response.comment.id
                            }">
                                ${response.attachments
                                    .map(
                                        (attachment) => `
                                        <div class="comment-attachment-file" data-file-id="${
                                            attachment.id
                                        }" data-comment-id="${
                                            response.comment.id
                                        }">
                                            <a href="${
                                                imageExtensions.has(
                                                    attachment.file_type.toLowerCase()
                                                )
                                                    ? attachment.file_path
                                                    : "/images/file.svg"
                                            }" data-fancybox>
                                                <img src="${
                                                    imageExtensions.has(
                                                        attachment.file_type.toLowerCase()
                                                    )
                                                        ? attachment.file_path
                                                        : "/images/file.svg"
                                                }" width="100" height="100" alt="${
                                            imageExtensions.has(
                                                attachment.file_type.toLowerCase()
                                            )
                                                ? "Picture"
                                                : "File"
                                        }" class="object-cover"/>
                                            </a>
                                            <div class="action-btns">
                                                <a href="${
                                                    attachment.file_path
                                                }" class="file-download" download="file">
                                                    <img src="/images/icons/file-download.svg">
                                                </a>
                                                <a class="comment-attachment-link" data-fancybox href="${
                                                    attachment.file_path
                                                }">
                                                    <span class="reviewImage">
                                                        <img src="/images/icons/file-view.svg">
                                                    </span>
                                                </a>
                                            </div>
                                        </div>
                                    `
                                    )
                                    .join("")}
                            </div>
                        </div>
                        <div class="d-flex justify-content-start commentActionDiv comment-action-div-css">
                            ${
                                isOwner && !isReply
                                    ? `
                                <a href="#!" class="me-3 text-decoration-none edit-comment" data-comment-id="${response.comment.id}">
                                    <p class="mb-0 text-xs font-medium text-gray-500">Edit</p>
                                </a>
                            `
                                    : ""
                            }
                            ${
                                !isReply
                                    ? `
                                <a href="#!" class="me-3 text-decoration-none reply-comment" data-comment-id="${response.comment.id}">
                                    <p class="mb-0 text-xs font-medium text-gray-500">Reply</p>
                                </a>
                            `
                                    : ""
                            }
                            ${
                                isOwner
                                    ? `
                                <a href="#!" class="me-3 text-decoration-none delete-comment" data-comment-id="${response.comment.id}">
                                    <p class="mb-0 text-xs font-medium text-gray-500">Delete</p>
                                </a>
                            `
                                    : ""
                            }
                        </div>
                        <div id="replies-${response.comment.id}"></div>
                    </div>
                </div>
            </div>
        `;

        if (isReply) {
            $(`#replies-${response.comment.parent_id}`).append(commentHtml);
            $(".reply-comment-form").html("").hide();
            removeEditMessageClass(response.comment.parent_id);
            $(`#comment-${response.comment.parent_id}`)
                .find(".commentActionDiv")
                .show();
        } else {
            $(".commentsList").append(commentHtml);
            fetchUnreadCommentsCount(projectId);
        }
    }

    function updateTaggedUsersForEdit(taggedUsersInput, editableDiv) {
        var taggedIds = [];
        editableDiv.find(".mention-span").each(function () {
            var id = $(this).data("user-id");
            if (id) {
                taggedIds.push(id);
            }
        });
        taggedUsersInput.val(taggedIds.join(","));
    }

    function handleCommentAction(actionType, commentId, commentContainer) {
        var commentContentElement;
        if (actionType === "edit") {
            commentContentElement = commentContainer.find(".comment-content");
        } else if (actionType === "reply") {
            commentContentElement = commentContainer
                .find(".comment-listing-text")
                .first();
        } else {
            console.error("Invalid action type: " + actionType);
            return;
        }
        commentContainer
            .find(".comment-listing-text")
            .first()
            .addClass("edit-message");

        if (commentContentElement.length > 0) {
            commentContentElement.addClass("edit-message");
            var formHtml;
            var saveButtonClass;
            if (actionType === "edit") {
                var commentContent = commentContentElement.html().trim();
                var attachmentsHtml = "";
                var supportedImageTypes = [
                    "jpeg",
                    "png",
                    "gif",
                    "webp",
                    "jpg",
                    "bmp",
                ];

                commentContainer
                    .find(
                        `.comment-attachment-file[data-comment-id="${commentId}"]`
                    )
                    .each(function () {
                        var fileUrl = $(this).find("a").attr("href");
                        var fileType = fileUrl.split(".").pop().toLowerCase();
                        var imgSrc = supportedImageTypes.includes(fileType)
                            ? fileUrl
                            : "/images/file.svg";
                        var fileId = $(this).data("file-id");
                        attachmentsHtml += `
                    <div class="attachment-item" data-index="${fileId}" data-file-id="${fileId}">
                        <img src="${imgSrc}" alt="Attachment">
                        <span class="remove-attachment" data-comment-id="${commentId}" data-file-id="${fileId}" data-index="${fileId}">
                            <span>&times;</span>
                        </span>
                    </div>
                `;
                    });

                commentContainer
                    .find(
                        `.comment-attachment-file[data-comment-id="${commentId}"]`
                    )
                    .hide();

                formHtml = `
                <div class="edit-comment-form edit-reply-message-box-css position-relative">

                    <div class="editable-comment-input message-input editable-input-css text-gray-800 hitEnterCls" data-actionType="${actionType}" contenteditable="true" id="editInput${commentId}">${commentContent}</div>
                    <div class="message-file-input-container attachment-icon position-absolute">
                        <input type="file" name="edit-file-input" id="edit_file_input${commentId}" class="message-file-input" multiple />
                        <label class="message-file-input-label" for="edit_file_input${commentId}">
                            <img src="/images/icons/paperclip.svg" alt="Upload">
                        </label>
                    </div>

                    <div id="edit-attachment-preview${commentId}" class="attachment-preview attachment-preview-inner">${attachmentsHtml}</div>
                    <div class="d-flex algin-items-center flex-wrap flex-md-nowrap justify-content-end gap-3 ms-auto mt-2">
                        <button class="btn btn-secondary theme-btn cancel-edit-comment" data-comment-id="${commentId}" type="button">Cancel</button>
                        <button class="btn btn-primary theme-btn save-edit-btn-css save-edit-comment" type="button" data-comment-id="${commentId}" disabled>Save</button>
                    </div>
                </div>
            `;
                saveButtonClass = ".save-edit-comment";
            } else if (actionType === "reply") {
                formHtml = `
                <div class="reply-comment-form edit-reply-message-box-css position-relative">

                    <div class="editable-comment-input message-input editable-input-css hitEnterCls" data-actionType="${actionType}" placeholder="Type to reply" contenteditable="true" id="replyInput${commentId}" data-tribute="true" ></div>
                    <div class="message-file-input-container attachment-icon position-absolute">
                        <input type="file" name="reply-file-input" id="reply_file_input${commentId}" class="message-file-input" multiple />
                        <label class="message-file-input-label" for="reply_file_input${commentId}">
                            <img src="/images/icons/paperclip.svg" alt="Upload">
                        </label>
                    </div>

                    <div id="reply-attachment-preview${commentId}" class="attachment-preview attachment-preview-inner"></div>
                    <div class="d-flex algin-items-center flex-wrap flex-md-nowrap justify-content-end gap-3 ms-auto mt-2">
                        <button class="btn btn-secondary theme-btn cancel-reply-comment" type="button" data-comment-id="${commentId}">Cancel</button>
                        <button class="btn btn-primary theme-btn save-edit-btn-css reply-message-btn" type="button" data-comment-id="${commentId}" disabled>Save</button>
                    </div>
                </div>
            `;
                saveButtonClass = ".reply-message-btn";
            }

            commentContentElement.append(formHtml);
            var editableDiv = commentContentElement.find(
                ".editable-comment-input"
            );
            var editTaggedUsersInput = $("<input>", {
                type: "hidden",
                id: "edit_tagged_users",
                name: "tagged_users",
            });
            commentContentElement.append(editTaggedUsersInput);
            addMentionFunctionalityForEdit(
                editableDiv,
                editTaggedUsersInput,
                commentId
            );

            // Function to update the save button state
            function updateSaveButton() {
                var text = editableDiv.text().trim();
                var hasFiles =
                    editFiles[commentId]?.length > 0 ||
                    replyFiles[commentId]?.length > 0 ||
                    (removedFiles[commentId] &&
                        removedFiles[commentId].length > 0);
                var saveButton = commentContentElement.find(saveButtonClass);
                saveButton.prop("disabled", text === "" && !hasFiles);
            }

            editableDiv.on("input", updateSaveButton);

            // Event listener for file input changes
            $(document).on(
                "change",
                `input[name='edit-file-input']`,
                function (e) {
                    var commentId = $(this).attr("id").replace(/\D/g, "");
                    if (!editFiles[commentId]) {
                        editFiles[commentId] = [];
                    }
                    var editAttachmentPreview = $(
                        `#edit-attachment-preview${commentId}`
                    );
                    handleFileInputChange(
                        e,
                        editFiles[commentId],
                        editAttachmentPreview,
                        commentId
                    );
                    updateSaveButton();
                }
            );

            $(document).on(
                "change",
                `input[name='reply-file-input']`,
                function (e) {
                    var commentId = $(this).attr("id").replace(/\D/g, "");
                    if (!replyFiles[commentId]) {
                        replyFiles[commentId] = [];
                    }
                    var replyAttachmentPreview = $(
                        `#reply-attachment-preview${commentId}`
                    );
                    handleFileInputChange(
                        e,
                        replyFiles[commentId],
                        replyAttachmentPreview,
                        commentId
                    );
                    updateSaveButton();
                }
            );

            $(document).on("click", ".remove-attachment", function () {
                var commentId = $(this).data("comment-id");

                if (commentId) {
                    // Check if we're in the edit context
                    var editAttachmentPreview = $(
                        `#edit-attachment-preview${commentId}`
                    );
                    if (editFiles[commentId]) {
                        handleAttachmentRemoval(
                            editFiles[commentId],
                            editAttachmentPreview,
                            commentId
                        );
                    }
                } else {
                    // We're in the reply context
                    var replyAttachmentPreview = $(
                        `#reply-attachment-preview${commentId}`
                    );
                    if (replyFiles[commentId]) {
                        handleAttachmentRemoval(
                            replyFiles[commentId],
                            replyAttachmentPreview,
                            commentId
                        );
                    }
                }

                updateSaveButton();
            });

            var commentActionDiv = commentContainer.find(".commentActionDiv");
            if (commentActionDiv.length > 0) {
                commentActionDiv[0].style.setProperty(
                    "display",
                    "none",
                    "important"
                );
            } else {
                console.error(
                    ".commentActionDiv not found for comment ID: " + commentId
                );
            }
        } else {
            console.error(
                "Comment content element not found for comment ID: " + commentId
            );
        }
    }

    // Add the event listener for the Enter key press using jQuery
    $(document).on("keydown", ".hitEnterCls", function (event) {
        if (event.key === "Enter") {
            // Check for key combinations
            if (event.metaKey || event.shiftKey) {
                // Command (Mac) or Shift (Windows) + Enter: Insert a newline
                document.execCommand("insertText", false, "\n");
                event.preventDefault(); // Prevent default action to avoid extra line break
            } else {
                // Just Enter: Trigger save action
                event.preventDefault(); // Prevent default Enter behavior (e.g., form submission)
                let commentId = this.id.replace(/\D/g, ""); // Extract the comment ID from the input ID
                let actionType = $(this).data("actiontype"); // Get the actionType from the data attribute
                if (actionType === "edit") {
                    console.log("Edit action");
                    $(
                        `.save-edit-comment[data-comment-id='${commentId}']`
                    ).click();
                } else if (actionType === "reply") {
                    console.log("Reply action");
                    $(
                        `.reply-message-btn[data-comment-id='${commentId}']`
                    ).click();
                }
            }
        }
    });

    function nameInitialByName(firstName, lastName = null) {
        if (lastName) {
            return substrString(firstName + " " + lastName);
        } else {
            return firstName.charAt(0).toUpperCase();
        }
    }

    // Function to fetch comment users dynamically
    function getCommentUsers(callback) {
        $.ajax({
            url: "/comments/users",
            type: "GET",
            success: function (response) {
                if (response && Array.isArray(response)) {
                    callback(response);
                } else {
                    console.error("Invalid response format:", response);
                    callback([]);
                }
            },
            error: function (error) {
                console.error("Error fetching comment users:", error);
                callback([]);
            },
        });
    }

    $("#comments").on("click", function () {
        markCommentsAsRead(projectId);
    });

    function fetchUnreadCommentsCount(projectId) {
        $.ajax({
            url: "/comments/unread-count",
            type: "GET",
            data: {
                project_id: projectId,
            },
            success: function (response) {
                console.log("response", response);
                const unreadCountElement = $("#unread-comments-count");

                if (response.unread_count > 0) {
                    unreadCountElement.text(" (" + response.unread_count + ")");
                    unreadCountElement.attr("data-new-label", "NEW"); // Add "NEW" label
                    unreadCountElement.removeClass("d-none"); // Display the element
                } else {
                    unreadCountElement.text("");
                    unreadCountElement.removeAttr("data-new-label"); // Remove "NEW" label
                    unreadCountElement.addClass("d-none"); // Hide the element when count is 0
                }
            },
            error: function (response) {
                console.log("Error:", response);
            },
        });
    }

    function markCommentsAsRead(projectId) {
        $.ajax({
            url: "/comments/mark-as-read",
            type: "POST",
            data: {
                project_id: projectId,
            },
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function (response) {
                $("#unread-comments-count").text("");
                $("#unread-comments-count").addClass("d-none");
            },
            error: function (response) {
                console.log("Error:", response);
            },
        });
    }

    // Event handler for edit-comment
    $(document).on("click", ".edit-comment", function (e) {
        e.preventDefault();
        var commentId = $(this).data("comment-id");
        var commentContainer = $("#comment-" + commentId);
        handleCommentAction("edit", commentId, commentContainer);
    });

    // Event handler for reply-comment
    $(document).on("click", ".reply-comment", function (e) {
        e.preventDefault();
        var commentId = $(this).data("comment-id");
        var commentContainer = $("#comment-" + commentId);
        handleCommentAction("reply", commentId, commentContainer);
    });

    // Function to remove 'edit-message' class when needed
    function removeEditMessageClass(commentId) {
        var commentContainer = $("#comment-" + commentId); // Assuming each comment has an id like 'comment-1', 'comment-2', etc.
        var commentContentElement = commentContainer.find(
            ".comment-content, .comment-listing-text"
        );
        commentContentElement.removeClass("edit-message");
        $(".commentActionDiv").show();
        $(".comment-content, .comment-listing-text").removeClass(
            "edit-message"
        );
    }

    // Event handler for canceling comment edit
    $(document).on("click", ".cancel-edit-comment", function () {
        var commentId = $(this).data("comment-id");
        var commentContainer = $("#comment-" + commentId);
        console.log(commentContainer);
        var originalContent = commentContainer
            .find(".editable-comment-input")
            .data("original-content");
        commentContainer.find(".comment-content").html(originalContent);
        commentContainer.find(".commentActionDiv").show();
        commentContainer.find(".comment-attachment").show();
        commentContainer.find(".comment-attachment-file").css("display", "");
        $(".edit-comment-form").html("").hide();
        removeEditMessageClass(commentId);
    });

    // Event handler for canceling comment reply
    $(document).on("click", ".cancel-reply-comment", function () {
        var commentId = $(this).data("comment-id");
        $(".reply-comment-form").html("").hide();
        $("#comment-" + commentId)
            .find(".commentActionDiv")
            .show();
        removeEditMessageClass(commentId);
    });

    // Event handler for saving edited comment
    $(document).on("click", ".save-edit-comment", function () {
        var commentId = $(this).data("comment-id");
        var commentContainer = $("#comment-" + commentId);
        var editableDiv = commentContainer
            .find(".editable-comment-input")
            .first();
        var editedContent = editableDiv.html().trim();
        var taggedUserIds = $("#edit_tagged_users").val();
        var filesToSend = editFiles[commentId] || [];
        var filesToSend = editFiles[commentId] || [];

        var formData = new FormData();
        formData.append("_token", $('meta[name="csrf-token"]').attr("content"));
        formData.append("content", editedContent);
        formData.append("taggedUserIds", taggedUserIds);
        $.each(filesToSend, function (i, file) {
            formData.append("files[]", file);
        });

        if (removedFiles[commentId] && removedFiles[commentId].length > 0) {
            formData.append(
                "removedFiles",
                JSON.stringify(removedFiles[commentId])
            );
        }

        $.ajax({
            url: "/comments/edit/" + commentId,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (response.success) {
                    // Update comment content
                    commentContainer
                        .find(".comment-content")
                        .first()
                        .html(editedContent);

                    // Update attachments
                    var attachmentHtml = "";
                    if (response.attachments) {
                        response.attachments.forEach(function (attachment) {
                            var fileType = attachment.file_type;
                            var filePath = attachment.file_path;
                            var imgSrc = [
                                "jpeg",
                                "png",
                                "gif",
                                "webp",
                                "jpg",
                                "bmp",
                            ].includes(fileType)
                                ? filePath
                                : "/images/file.svg";

                            attachmentHtml += `
                            <div class="comment-attachment-file" data-comment-id="${commentId}">
                                <a href="${filePath}" data-fancybox data-file-id="${attachment.id}">
                                    <img src="${imgSrc}" width="100" height="100" alt="Attachment" />
                                </a>
                                <div class="action-btns">
                                    <a href="${filePath}" class="file-download" download="file" data-file-id="${attachment.id}">
                                        <img src="/images/icons/file-download.svg">
                                    </a>
                                    <a class="comment-attachment-link" data-fancybox href="${filePath}">
                                        <span class="reviewImage">
                                            <img src="/images/icons/file-view.svg">
                                        </span>
                                    </a>
                                </div>
                            </div>
                        `;
                        });
                    }
                    commentContainer
                        .find(
                            `.comment-attachment[data-comment-id="${commentId}"]`
                        )
                        .html(attachmentHtml);

                    // Show comment actions again
                    commentContainer.find(".commentActionDiv").show();
                    commentContainer.find(".comment-attachment").show();
                    removeEditMessageClass(commentId);
                    // Clear the edit files array
                    editFiles[commentId] = [];
                } else {
                    console.error("Failed to update comment:", response.error);
                }
            },
            error: function (error) {
                console.error("Error editing comment:", error);
                commentContainer.find(".commentActionDiv").show();
            },
        });
    });

    // Event handler for deleting a comment
    $(document).on("click", ".delete-comment", function (e) {
        e.preventDefault();
        var commentId = $(this).data("comment-id");

        Swal.fire({
            title: "Are you sure to delete this Comment?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            //     confirmButtonColor: "#3085d6",
            //  cancelButtonColor: "#d33",
            confirmButtonText: "Delete",
            cancelButtonText: "Cancel",
            customClass: {
                container: "swal2-container custom-swal2-container",
            },
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: "/comments/delete/" + commentId,
                    type: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    success: function (response) {
                        $("#comment-" + commentId).remove();
                        $("#reply-" + commentId).remove();
                        Swal.fire({
                            toast: true,
                            title: "Deleted!",
                            text: "Your comment has been deleted.",
                            animation: false,
                            position: "top-right",
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true,
                            customClass: {
                                popup: "success",
                            },
                            html: `
                                <div class="custom-toast">
                                    <span class="custom-toast-close"><img src="../images/icons/alert-cross-icon.svg" alt="cross"></span>
                                    <div class="custom-toast-content">
                                        <p>Your comment has been deleted.</p>
                                    </div>
                                </div>
                            `,

                            didOpen: (toast) => {
                                toast.addEventListener(
                                    "mouseenter",
                                    Swal.stopTimer
                                );
                                toast.addEventListener(
                                    "mouseleave",
                                    Swal.resumeTimer
                                );
                            },
                        });
                        document
                            .querySelector(".swal2-container")
                            .classList.add("post-delete-class");
                        console.log("Comment deleted successfully!");

                        deleteMessageSocket(commentId);
                    },
                    error: function (error) {
                        Swal.fire(
                            "Error!",
                            "There was an error deleting the comment.",
                            "error"
                        );
                        console.error("Error deleting comment:", error);
                    },
                });
            }
        });
    });

    $(".message-input").keydown(function (event) {
        if (event.keyCode == 13 && !event.shiftKey) {
            event.preventDefault(); // Prevent newline in textarea

            var tributeMenu = $(".tribute-container");
            if (tributeMenu.is(":visible")) {
                // If Tribute menu is visible, select the first item
                var firstItem = tributeMenu.find(".tribute-item:first-child");
                if (firstItem.length) {
                    firstItem.trigger("mousedown");
                }
            } else {
                // If no Tribute menu, send the message
                $(".send-message-btn").click();
            }
        }
    });

    function deleteMessageSocket(commentId) {
        var data = JSON.stringify({
            project: "ECNL",
            title: "ECNL comments",
            description: "",
            data: {
                delete_id: commentId,
            },
        });

        $.ajax({
            url: "https://socket.crebos.online/send-socket",
            method: "POST",
            headers: {
                Authorization:
                    "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpZCI6IjJhYmUyYjUyLWQ3ZjAtNDNkNi1hYzFjLTE0OWQ2N2ExNTQxMyIsInVzZXJuYW1lIjoiY3JlYm9zQWRtaW5VU2VyIiwiZW1haWwiOiJhZG1pbkBjcmVib3MuY29tIiwiaWF0IjoxNzAyNTM4NTYwfQ.dHdoRlT8RWk_xn75T98xnFRorQVR55_Ud0sY8hwvFO4",
                "Content-Type": "application/json",
            },
            data: data,
            success: function (response) {
                console.log(JSON.stringify(response));
            },
            error: function (xhr, status, error) {
                console.log(error);
            },
        });
    }

    // Initialize mentions functionality
    function initializeMentions(editableDiv, sendMessageCallback) {
        getCommentUsers(function (users) {
            var tribute = new Tribute({
                values: function (text, callback) {
                    var filteredUsers = users
                        .filter(function (user) {
                            return user.name
                                .toLowerCase()
                                .startsWith(text.toLowerCase());
                        })
                        .map(function (user) {
                            return { key: user.name, value: user.id };
                        });
                    callback(filteredUsers);
                },
                selectTemplate: function (item) {
                    if (item && item.original) {
                        return `<span contenteditable="false" class="mention-span" data-id="${item.original.value}">@${item.original.key}</span>&nbsp;`;
                    } else {
                        console.error("Invalid item structure:", item);
                        return "";
                    }
                },
                menuItemTemplate: function (item) {
                    if (item && item.original) {
                        return item.original.key;
                    } else {
                        console.error("Invalid item structure:", item);
                        return "";
                    }
                },
            });

            // Handle tribute-replaced event
            editableDiv.on("tribute-replaced", function (e) {
                var mention = e.detail.item;
                if (mention && mention.original) {
                    var taggedUser = $(
                        `<span contenteditable="false" class="mention-span" data-id="${mention.original.value}">@${mention.original.key}</span>&nbsp;`
                    );
                    var selection = window.getSelection();
                    var range = selection.getRangeAt(0);

                    // Insert the new mention at the current cursor position
                    range.deleteContents();
                    range.insertNode(taggedUser[0]);

                    // Move the cursor to the end of the inserted mention
                    range.setStartAfter(taggedUser[0]);
                    range.setEndAfter(taggedUser[0]);

                    // Update the selection
                    selection.removeAllRanges();
                    selection.addRange(range);
                } else {
                    console.error("Invalid mention structure:", mention);
                }
            });

            // Handle backspace to remove tagged users
            editableDiv.on("keydown", function (e) {
                if (e.keyCode === 8) {
                    var selection = window.getSelection();
                    var range = selection.getRangeAt(0);
                    var startContainer = range.startContainer;

                    if (
                        startContainer.nodeType === Node.TEXT_NODE &&
                        startContainer.textContent === ""
                    ) {
                        var previousSibling = startContainer.previousSibling;
                        if (
                            previousSibling &&
                            previousSibling.classList.contains("mention-span")
                        ) {
                            previousSibling.remove();
                            e.preventDefault();
                        }
                    } else if (
                        startContainer.nodeType === Node.ELEMENT_NODE &&
                        startContainer.classList.contains("mention-span")
                    ) {
                        startContainer.remove();
                        e.preventDefault();
                    }
                }
            });

            // Handle Enter key to auto-select first mention or send message
            editableDiv.on("keydown", function (e) {
                if (e.keyCode === 13 && !e.shiftKey) {
                    e.preventDefault(); // Prevent newline in textarea

                    var tributeMenu = $(".tribute-container");
                    if (tributeMenu.is(":visible")) {
                        var firstItem = tributeMenu.find(
                            ".tribute-item:first-child"
                        );
                        if (firstItem.length) {
                            firstItem.trigger("mousedown");
                        }
                    } else {
                        sendMessageCallback();
                    }
                }
            });

            tribute.attach(editableDiv[0]);
        });
    }

    // Usage for editing comments
    function addMentionFunctionalityForEdit(
        editableDiv,
        hiddenInput,
        commentId
    ) {
        initializeMentions(editableDiv, function () {
            console.log(`Message sent for comment ID: ${commentId}`);
        });
    }
});
