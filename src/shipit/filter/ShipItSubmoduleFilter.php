  <<TestsBypassVisibility>>
    ?string $old_rev,
    ?string $new_rev,
    if ($old_rev === null && $new_rev !== null) {
      printf(
        "  Adding submodule at '%s'.\n",
        $path,
      );
      return sprintf(
        'new file mode 16000
index 0000000..%s 160000
--- /dev/null
+++ b/%s
@@ -0,0 +1 @@
+Subproject commit %s
',
        $new_rev,
        $path,
        $new_rev,
      );
    } else if ($new_rev === null && $old_rev !== null) {
      printf(
        "  Removing submodule at '%s'.\n",
        $path,
      );
      return sprintf(
        'deleted file mode 160000
index %s..0000000
--- a/%s
+++ /dev/null
@@ -1 +0,0 @@
-Subproject commit %s
',
        $old_rev,
        $path,
        $old_rev,
      );
    } else {
      return sprintf(
        'index %s..%s 160000
--- a/%s
+++ b/%s
@@ -1 +1 @@
-Subproject commit %s
+Subproject commit %s
',
        $old_rev,
        $new_rev,
        $path,
        $path,
        $old_rev,
        $new_rev,
      );
    }