<Service_GetCancelPolicy>
    <?php $this->renderPartial('/layouts/req_agentlogin'); ?>
    <GetCancelPolicy_Request>
        <ResNo><?php echo $ResNo; ?></ResNo>
        <OSRefNo><?php echo $OSRefNo; ?></OSRefNo>
        <HBookId><?php echo $HBookId; ?></HBookId>
        <clxDate></clxDate>
    </GetCancelPolicy_Request>
</Service_GetCancelPolicy>
